var selectedQuestionId = 0;
var url = "";
// We can't use $ with Wordpress!
jQuery(document).ready(function() {
  url = WPURLS.siteurl;
  console.log(url);
  console.log("Script sourced. Gnomesayin'?");
  refreshQuestions();
  
  jQuery(".answer_container").hide();
  jQuery("#aform_container").hide();
  addClickHandlers();

});

function addClickHandlers() {
  // Forms
  jQuery("#question_form").on('submit', function(event){
    event.preventDefault();
    submitQuestion(jQuery("#question_text").val());
  });
  
  jQuery("#answer_form").on('submit', function(event){
    event.preventDefault();
    submitAnswer(jQuery("#answer_text").val());
  });
  // Buttons
  jQuery("#question_table").on('click', '.answers', function(){
    console.log(jQuery(this).data('id'));
    jQuery("#answer_table").empty();
    refreshAnswers(jQuery(this).data('id'));
    jQuery("#qform_container").hide();
    jQuery("#question_container").hide();
    
    jQuery("#aform_container").show();
    jQuery("#answer_container").show();
  });
  jQuery("#aform_container").on('click', '#back_button', function(){
    jQuery("#qform_container").show();
    jQuery("#question_container").show();
    
    jQuery("#aform_container").hide();
    jQuery("#answer_container").hide();
  });
  jQuery("#question_table").on('click', '.upvote', function(){
    console.log(jQuery(this).data('id'));
    upvoteQuestion(jQuery(this).data('id'));
  });
    jQuery("#answer_table").on('click', '.upvote', function(){
    console.log(jQuery(this).data('id'));
    upvoteAnswer(jQuery(this).data('id'));
  });
}

function upvoteQuestion(id) {
  jQuery.ajax({
    url: url + "/wp-json/gnomesayin/v1/questions/upvote/",
    method: "POST",
    data: { question_id: id },
    success: function(response) {
      refreshQuestions();
    }
  });
}

function upvoteAnswer(id) {
  jQuery.ajax({
    url: url + "/wp-json/gnomesayin/v1/answers/upvote/",
    method: "POST",
    data: { answer_id: id },
    success: function(response) {
      refreshAnswers(selectedQuestionId);
    }
  });
}

function refreshAnswers(id) {
  selectedQuestionId = id;
  
  // Get answers
  jQuery.ajax({
    url: url + "/wp-json/gnomesayin/v1/answers/",
    method: "GET",
    data: { question_id: id },
    success: function(response) {
      // Normally $("#answer_table")
      jQuery("#answer_table").empty();
        console.log("number_of_answers"+response.length);
      if(response.length == 0){
          jQuery("#answer_table").append("There are currently no answers.") 
      } 
      response.forEach(function(answer) {
        console.log(answer);
        jQuery("#answer_table").append("<div class='answer_row'>");
        jQuery("#answer_table").append("<p>" + answer.answer + "</p>");
        jQuery("#answer_table").append("<button class='upvote' data-id='" + answer.id + "'>upvote</button> <span>Upvotes: " + answer.up_vote + "</span>");
        jQuery("#question_table").append("</div>");
      });
    }
  });
  // End get answers
}

function submitQuestion(question_text) {
  // Add question
  jQuery.ajax({
    url: url + "/wp-json/gnomesayin/v1/questions/",
    method: "POST",
    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
    beforeSend: function ( xhr ) {
      if(typeof wpApiSettings != "undefined") {
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
      }
    },
    data: { question: question_text },
    success: function(data) {
      console.log(data);
      refreshQuestions();
      clearQuestion();
        

    }
  });
  // End add question
}

function clearQuestion() {
      jQuery("#question_text").val('');
        console.log("clearQuestion fired off");
};

function submitAnswer(answer_text) {
  // Add answer
  jQuery.ajax({
    url: url + "/wp-json/gnomesayin/v1/answers/",
    method: "POST",
    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
    beforeSend: function ( xhr ) {
      if(typeof wpApiSettings != "undefined") {
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
      }
    },
    data: { question_id: selectedQuestionId, answer: answer_text },
    success: function(data) {
      console.log(data);
      refreshAnswers(selectedQuestionId);
    }
  });
  // End add answer
}

function refreshQuestions() {
  
  // Get questions
  jQuery.ajax({
    url: url + "/wp-json/gnomesayin/v1/questions/",
    method: "GET",
    success: function(response) {
      // Step 1: Empty existing content
      // Normally $("#question_table")
      jQuery("#question_table").empty();
      response.forEach(function(question) {
        console.log(question);
        console.log(question.question);
   
        jQuery("#question_table").append("<div class='question_row'>");
        jQuery("#question_table").append("<p>" + question.question + "</p>");
jQuery("#question_table").append("<button class='upvote' data-id='" + question.id + "'><img src='" + url + "/wp-content/plugins/gnomesayin/icons/ic_thumb_up_black_24dp_1x.png' style='width:32px; height:32px;'> upvote </button> Upvotes: " + question.up_vote + "<br><button class='answers' data-id='" + question.id + "'>View Answers (" + question.answer_count + ")</button>");
        jQuery("#question_table").append("</div>");
          
        // Step 2: Add each row back to the screen
      });
    }
  });
  // End get questions
}