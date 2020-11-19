<?php

namespace App\Http\Controllers;

use App\Model\TableEntity;
use App\Model\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function messageList(Request $request, $arg = null){
        if($arg != null){
            $this->mproxy->updateMessageReadFlag($arg, $this->user->email);
            $messageDetail = $this->mproxy->getMessageDetail($arg);
            $replyList = $this->mproxy->getMessageReplies($arg);
        }
        $messages = $this->mproxy->getMessageList($this->user->email);
        return view('message_list', ['messages' => $messages, 'messageDetail' => $messageDetail ?? null, 'replyList' => $replyList ?? null]);
    }

    public function pushNotifications(Request $request, $arg = null){
        $pushList = $this->mproxy->getPushNotifications();
        if($arg != null){
            $this->mproxy->resendPushNotification($arg);
            return view('push_notification_list', ['pushList' => $pushList, 'msg' => $this->prepareMessage(true,'Message Sent Successfully!')]);
        }
        return view('push_notification_list', ['pushList' => $pushList]);
    }

    public function newPushNotifications(){
        return view('push_notification_add');
    }

    public function sendPushNotifications(Request $request){
        return $this->mproxy->publishNewPushNotification($request->input(), $this->user->email);
    }

    public function bulkSms(){
        $userList = $this->mproxy->getUsersList();
        $balance = $this->mproxy->getBulkSmsBalance();
        $smsStat = $this->mproxy->getBulkSmsStat();
        return view('bulk_sms', ['userList' => $userList, 'balance' => $balance, 'smsStat' => $smsStat]);
    }

    public function sendBulkSms(Request $request){
        $res = $this->mproxy->sendBulkSms($request->input());
        $isSuccessful = ($res == 146);
        return back()->with('msg', $this->prepareMessage($isSuccessful, $isSuccessful ? "Message Sent Successfully" : "Message could not be sent...Please check your balance" ));
    }

    public function saveReply(Request $request){
        $reply_id = $this->mproxy->getRef();
        $this->mproxy->saveMessageReply($request->input(), $this->user->email, $reply_id);
        $this->mproxy->sendSupportReplyPushNotification($reply_id);
        return back();
    }

    public function closeMsgThread(Request $request, $arg){
        return $this->mproxy->updateThreadStatus($arg, TicketStatus::closed);
    }

    public function openMsgThread(Request $request, $arg){
        return $this->mproxy->updateThreadStatus($arg, TicketStatus::opened);
    }

    public function composeMail(){
        return view('mail_compose');
    }

    public function sendMail(Request $request){
        $inputs = $request->input();
        return $this->mproxy->sendMailToSpecifiedUsers($inputs);
    }

    public function sentMail(){
        $mails = $this->mproxy->getAllSentMails();
        return view('mail_sent', ['mails' => $mails]);
    }

    public function draftMail(){
        $mails = $this->mproxy->getDraftMails();
        return view('mail_draft', ['mails' => $mails]);
    }

    public function saveDraftMail(Request $request){
        $inputs = $request->input();
        return $this->mproxy->saveMailDraft($inputs);
    }

    public function readMail(Request $request, $arg){
        $mail = $this->mproxy->getMailById($arg);
        return view('mail_read', ['mail' => $mail]);
    }

}
