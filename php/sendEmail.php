<?php

$from = $_POST['email'];
$sendTo = 'info@centroestudosmatex.pt';
$subject = 'Nova mensagem recebida através do seu site';

// form field names and their translations.
// array variable name => Text to appear in the email
$allowedFields = [
    'fname' => 'Nome',
    'lname' => 'Apelido',
    'tel' => 'Telemóvel',
    'eaddress' => 'Email',
    'message' => 'Menssagem'
];

$okMessage = 'Mensagem submetida com sucesso. Obrigado, será contactado em breve';
$errorMessage = 'Erro na submissão da mensagem. Por favor, tente outra vez mais tarde.';

try
{
    if(count($_POST) == 0) throw new \Exception('Formulário vazio');
            
    $emailText = $_POST['message'];

    foreach ($_POST as $key => $value) {
        // If the field exists in the $allowedFields array, include it in the email 
        if (isset($allowedFields[$key])) {
            $emailText .= "$allowedFields[$key]: $value\n";
        }
    }

    // All the neccessary headers for the email.
    $headers = [
        'Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    ];
    
    // Send email
    mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $responseArray = [
        'type' => 'success',
        'message' => $okMessage
    ];
}
catch (\Exception $e)
{
    $responseArray = [
        'type' => 'danger',
        'message' => $errorMessage
    ];
}

// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}