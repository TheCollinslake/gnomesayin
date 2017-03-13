
<?php
global $wpdb;

echo "<h2>Ask a question</h2>";

echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
echo "<input type='text' name='question'/>";
echo "<button type='submit'>Submit</button>";
echo "</form>";


//Copy and paste the below loop and update the question_text part to say question_id

if(isset($_POST['question'])) {
    echo "Thanks for asking, your question has been posted!";
    $user_id = 1;
	
	$table_name = $wpdb->prefix . 'gs_question';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'user_id' => $user_id, 
			'question_text' => $_POST['question']
		) 
	);

}

echo "<h2>Delete a question</h2>";

echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
echo "<input type='text' name='question_id'/>";
echo "<button type='submit'>Delete</button>";
echo "</form>";

if(isset($_POST['question_id'])) {
    echo "You have now removed the selected content.";
    $question_id = question_id;
	
	$table_name = $wpdb->prefix . 'gs_question';
	
	$wpdb->delete( 
		$table_name, 
		array( 
			'id' => $_POST['question_id']
		) 
	);

}

?>