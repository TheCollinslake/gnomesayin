// We can't use $ with Wordpress!
jQuery(document).ready(function() {
  console.log("Script sourced. Gnomesayin'?");
  
  // Add question
  jQuery.ajax({
    url: "http://localhost:8888/wp-json/gnomesayin/v1/questions/",
    method: "POST",
    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
    beforeSend: function ( xhr ) {
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
    },
    data: { question: "What time is it?" },
    success: function(data) {
      console.log(data);
    }
  });
  // End add question
  
  // Get questions
  jQuery.ajax({
    url: "http://localhost:8888/wp-json/gnomesayin/v1/questions/",
    method: "GET",
    success: function(response) {
      // Step 1: Empty existing content
      response.forEach(function(question) {
        console.log(question);
        console.log(question.question);
        // Step 2: Add each row back to the screen
      });
    }
  });
  // End get questions
  
  
  // Get answers
  jQuery.ajax({
    url: "http://localhost:8888/wp-json/gnomesayin/v1/answers/",
    method: "GET",
    data: { question_id: 1 },
    success: function(response) {
      // Step 1: Empty existing content
      response.forEach(function(answer) {
        console.log(answer);
        console.log(answer.answer);
        // Step 2: Add each row back to the screen
      });
    }
  });
  // End get answers
});