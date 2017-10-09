<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use pimax\FbBotApp;
use pimax\Messages\Message;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageButton;

class MessengerController extends Controller
{
    public function webhook()
    {
        $local_verify_token = env('WEBHOOK_VERIFY_TOKEN');
        $hub_verify_token = \Input::get('hub_verify_token');

        if($local_verify_token == $hub_verify_token) {
            return \Input::get('hub_challenge');
        }

        else return "Bad Verify token";
    }
    public function webhook_post()
    {
        //get message input
        $input = \Input::all();
        //\Log::info(print_r($input, 1));
        $recipient = $input['entry'][0]['messaging'][0]['sender']['id'];
        //$input_text = $input['entry'][0]['messaging'][0]['message']['text'];

        //create bot instance
        $token = env('PAGE_ACCESS_TOKEN'); 
        $bot = new FbBotApp($token);
        

        
        
        $text = "Great to see you at Linguician! Please select your native language! ¡Hola! Por favor, selecciona tu lengua materna.";
        $texta = "Cool! Which language do you want to learn?";
        $textb = "¡Genial! ¿Qué quieres hacer ahora?";
        $textc = "Te recomendemos las siguentes canciones:";
        $textd = "Awesome! Please sign up at Linguician or login with your Facebook Account by clicking on the button below!";

        


        //button Start Menu
        $buttons = [ 
             new MessageButton(MessageButton::TYPE_POSTBACK, "English", "English"),
             new MessageButton(MessageButton::TYPE_POSTBACK, "Español", "Spanish")
             ];

        //button English Selection
        $buttona = [ 
             new MessageButton(MessageButton::TYPE_POSTBACK, "Spanish", "es"),
             new MessageButton(MessageButton::TYPE_POSTBACK, "French", "fr"),
             new MessageButton(MessageButton::TYPE_POSTBACK, "Italian", "it")
             //new MessageButton(MessageButton::TYPE_POSTBACK, "German", "de"),
             //new MessageButton(MessageButton::TYPE_POSTBACK, "Portuguese", "pt")
             ];

        //button Spanish Selection
        $buttonb = [ 
             new MessageButton(MessageButton::TYPE_POSTBACK, "Ver canciones", "songsen"),
             new MessageButton(MessageButton::TYPE_WEB, "Crear una cuenta", "https://www.linguician.com/es/en/register")
             ];

        //button Song Selection
        $buttonc = [ 
             new MessageButton(MessageButton::TYPE_WEB, "Shape of You - Ed Sheeran", "https://www.linguician.com/es/en/play/39"),
             new MessageButton(MessageButton::TYPE_WEB, "You're Beautiful - James Blutn", "https://www.linguician.com/es/en/play/54")
             ];

        //button registration
        $buttond = [ 
             new MessageButton(MessageButton::TYPE_WEB, "Register", "https://www.linguician.com/en/es/register")
             ];
        $buttone = [ 
             new MessageButton(MessageButton::TYPE_WEB, "Register", "https://www.linguician.com/en/fr/register")
             ];
        $buttonf = [ 
             new MessageButton(MessageButton::TYPE_WEB, "Register", "https://www.linguician.com/en/it/register")
             ];


        $message = new StructuredMessage($recipient, StructuredMessage::TYPE_BUTTON, ['text' => $text, 'buttons' => $buttons]);

        // handle postback input, if there is any
        if(isset($input['entry'][0]['messaging'][0]['postback'])) {
           $payload = $input['entry'][0]['messaging'][0]['postback']['payload'];
           
            // in case the user speaks English
               if($payload == "English") {
                $message = new StructuredMessage($recipient, StructuredMessage::TYPE_BUTTON, ['text' => $texta, 'buttons' => $buttona]);
                    }

               elseif($payload == "es") {
                $message = new StructuredMessage($recipient, StructuredMessage::TYPE_BUTTON, ['text' => $textd, 'buttons' => $buttond]);
                    }
                elseif($payload == "fr") {
                $message = new StructuredMessage($recipient, StructuredMessage::TYPE_BUTTON, ['text' => $textd, 'buttons' => $buttone]);
                    }

                elseif($payload == "it") {
                $message = new StructuredMessage($recipient, StructuredMessage::TYPE_BUTTON, ['text' => $textd, 'buttons' => $buttonf]);
                    }
         
                
            //in case the user speaks Spanish
               elseif($payload == "Spanish") {
                $message = new StructuredMessage($recipient, StructuredMessage::TYPE_BUTTON, ['text' => $textb, 'buttons' => $buttonb]);
                    }
                    //in case the user wants songs
                elseif($payload == "songsen") {
                $message = new StructuredMessage($recipient, StructuredMessage::TYPE_BUTTON, ['text' => $textc, 'buttons' => $buttonc]);    
                    }
                
        } 
               
         
        // handle text input, if there is any
        elseif(isset($input['entry'][0]['messaging'][0]['message'])) {
            $input_text = $input['entry'][0]['messaging'][0]['message']['text'];
         
            // now do whatever you want with the text input
        }


        
        $bot->send($message);   
    }
}
