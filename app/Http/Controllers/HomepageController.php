<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PHPMailer;
use Illuminate\Support\Facades\Mail;

class HomepageController extends Controller
{
    public function index()
    {
        return view('main_page.home_page');
    }

    public function contact_page()
    {
        $msg='';
        return view('main_page.contact_page', compact('msg'));
    }

    public function gallery_page()
    {
        return view('main_page.gallery_page');
    }

    public function about_page()
    {
        return view('main_page.about_page');
    }

    public function marketing_plan()
    {
        return view('main_page.marketing_plan');
    }

    public function post_contact_page(Requests\ContactRequest $request)
    {
        $transport = \Swift_MailTransport::newInstance();
        // Create the message
        $message = \Swift_Message::newInstance();
        $message->setTo(array(
            'admin@wohhypermart.com' => 'WOH Administrator',
            'wohypermart@gmail.com' => 'WOH Administrator',
        ));
        $message->setCc(array('tatang.greatdev@gmail.com' => 'WOH Web Developer'));
        $message->setSubject('Inquiry from '.$request->number);
        $message->setBody($request->message);
        $message->setFrom($request->from_email, $request->from_name);

        // Send the email
        $mailer = \Swift_Mailer::newInstance($transport);
        $mailer->send($message);
        $msg='Thank you for visiting us. Your message has been sent! We will be in touch soon.';
        return view('main_page.contact_page', compact('msg'));
    }
}
