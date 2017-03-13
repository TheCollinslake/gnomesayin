// We can't use $ with Wordpress!
jQuery(document).ready(function() {
  console.log("Script sourced. Gnomesayin'?");
  refreshQuestions();
  
  jQuery("#question_form").on('submit', function(event){
    event.preventDefault();
    submitQuestion(jQuery("#question_text").val());
  });
  
  // Get answers
  jQuery.ajax({
    url: "http://localhost:8888/wp-json/gnomesayin/v1/answers/",
    method: "GET",
    data: { question_id: 1 },
    success: function(response) {
      // Step 1: Empty existing content
      response.forEach(function(answer) {
        //console.log(answer);
        //console.log(answer.answer);
        // Step 2: Add each row back to the screen
      });
    }
  });
  // End get answers
});

function submitQuestion(question_text) {
  // Add question
  jQuery.ajax({
    url: "http://localhost:8888/wp-json/gnomesayin/v1/questions/",
    method: "POST",
    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
    beforeSend: function ( xhr ) {
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
    },
    data: { question: question_text },
    success: function(data) {
      console.log(data);
    }
  });
  // End add question
}


function refreshQuestions() {
  // Get questions
  jQuery.ajax({
    url: "http://localhost:8888/wp-json/gnomesayin/v1/questions/",
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
        jQuery("#question_table").append("<button class='upvote' data-id='" + question.id + "'>upvote</button> <button class='answers' data-id='" + question.id + "'>Answers</button>");
        jQuery("#question_table").append("</div>");
        // Step 2: Add each row back to the screen
      });
    }
  });
  // End get questions
  
}