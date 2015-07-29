<?php include_once('common/session.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Toka is a chatroom-based social media platform. Connect now to join our family, make new friends, and talk about anything and everything.">
    <title><?php echo $user->username . ' - Toka'; ?></title>
    <?php include_once('common/header.php') ?>
    <script>
    /* DOM Ready */
    $(document).ready(function() {
        toka = new Toka();
        toka.ini();
    });
    </script>
</head>
<body>
    <div id="site">
        <section id="site-menu">
             <?php include_once('common/menu.php') ?>     
        </section>
        <section id="site-left-nav">
            <?php include_once('common/left_nav.php') ?>
        </section>
        <section id="site-content">
            <section id="site-subtitle">
                <div class="default-subtitle">Toka FAQ</div>
            </section>
            <section id="site-alert">
            </section>
            <div id="faq" style="font-family:'Montserrat'";>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-1" style="padding: 10px 15px !important;display:block;">1. What is Toka?</a>
                            </h4>
                        </div>
                        <div id="collapse-1" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <p>Toka is a Chatroom as a Service (CaaS). We are a platform first. This means we are open to any clever uses of our platform for various needs. However, our primary goal is to make it easier for people to connect with like-minded individuals and communities online.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-2" style="padding: 10px 15px !important;display:block;">2. What can I do on Toka?</a>
                            </h4>
                        </div>
                        <div id="collapse-2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Right now, our features are somewhat limited. But you can do the following:</p>
                                <ul>
                                    <li>Create a Chatroom (Max 1) - Why only 1? It is partly a "social" experiment and partly to do with making sure users do not create an excessive amount of chatrooms before a well-established communities are made. We will more than likely increase this limit in the near future.</li>
                                    <li>Personalize Your Chatroom</li>
                                    <li>Create a "Hashtag" Room</li>
                                    <li>Browse Categories of Chatrooms</li>
                                    <li>Chat</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-3" style="padding: 10px 15px !important;display:block;">3. What emotes does Toka have?</a>
                            </h4>
                        </div>
                        <div id="collapse-3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>We have a 3 sets of standard emotes, right now we are rotating with the cat set. The emote's text code shows if you hover over them, use this to display the emote in chat.</p>
                                <div>
                                    <span>Universal Emote Set:</span>
                                    <img title="o/" alt="o/" src="https://toka.io/assets/images/emotes/toka.png" class="emote">
                                    <img title="<3" alt="<3" src="https://toka.io/assets/images/emotes/heart.png" class="emote">
                                </div>
                                <div>
                                    <span>Cat Emote Set:</span>
                                    <img title=":)" alt=":)" src="https://toka.io/assets/images/emotes/standard/cat/CAT-smile.png" class="emote">
                                    <img title=":D" alt=":D" src="https://toka.io/assets/images/emotes/standard/cat/CAT-Dsmile.png" class="emote">
                                    <img title=":P" alt=":P" src="https://toka.io/assets/images/emotes/standard/cat/CAT-tongue.png" class="emote">
                                    <img title=";)" alt=";)" src="https://toka.io/assets/images/emotes/standard/cat/CAT-wink.png" class="emote">
                                    <img title=":(" alt=":(" src="https://toka.io/assets/images/emotes/standard/cat/CAT-frown2.png" class="emote">
                                    <img title="T_T" alt="T_T" src="https://toka.io/assets/images/emotes/standard/cat/CAT-cry.png" class="emote">
                                    <img title="-_-" alt="-_-" src="https://toka.io/assets/images/emotes/standard/cat/CAT-_-.png" class="emote">
                                    <img title=">:)" alt=">:)" src="https://toka.io/assets/images/emotes/standard/cat/CAT-evilsmile.png" class="emote">
                                    <img title=">:(" alt=">:(" src="https://toka.io/assets/images/emotes/standard/cat/CAT-angry.png" class="emote">
                                    <img title=":\" alt=":\" src="https://toka.io/assets/images/emotes/standard/cat/CAT-slash.png" class="emote">
                                    <img title="8) or B)" alt="8) or B)" src="https://toka.io/assets/images/emotes/standard/cat/CAT-cool.png" class="emote">
                                    <img title="catGasm" alt="catGasm" src="https://toka.io/assets/images/emotes/standard/cat/CAT-o.png" class="emote">
                                    <img title="catpa" alt="catpa" src="https://toka.io/assets/images/emotes/standard/cat/CAT-kappa.png" class="emote">
                                </div>
                                <div>
                                    <span>White Chat Bubble Emote Set:</span>
                                    <img title=":)" alt=":)" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wsmile.png" class="emote">
                                    <img title=":D" alt=":D" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-WDsmile.png" class="emote">
                                    <img title=":P" alt=":P" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wtongue.png" class="emote">
                                    <img title=";)" alt=";)" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wwink.png" class="emote">
                                    <img title=":(" alt=":(" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wfrown2.png" class="emote">
                                    <img title="T_T" alt="T_T" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wcry.png" class="emote">
                                    <img title="-_-" alt="-_-" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-W-_-.png" class="emote">
                                    <img title=">:)" alt=">:)" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wevilsmile.png" class="emote">
                                    <img title=">:(" alt=">:(" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wangry.png" class="emote">
                                    <img title=":\" alt=":\" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wslash.png" class="emote">
                                    <img title="8) or B)" alt="8) or B)" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wcool.png" class="emote">
                                    <img title="gasm" alt="gasm" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wo.png" class="emote">
                                    <img title="kappa" alt="kappa" src="https://toka.io/assets/images/emotes/standard/chat/white/CHAT-Wkappa.png" class="emote">
                                </div>
                                <div>
                                    <span>Green Chat Bubble Emote Set:</span>
                                    <img title=":)" alt=":)" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gsmile.png" class="emote">
                                    <img title=":D" alt=":D" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-GDsmile.png" class="emote">
                                    <img title=":P" alt=":P" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gtongue.png" class="emote">
                                    <img title=";)" alt=";)" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gwink.png" class="emote">
                                    <img title=":(" alt=":(" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gfrown2.png" class="emote">
                                    <img title="T_T" alt="T_T" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gcry.png" class="emote">
                                    <img title="-_-" alt="-_-" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-G-_-.png" class="emote">
                                    <img title=">:)" alt=">:)" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gevilsmile.png" class="emote">
                                    <img title=">:(" alt=">:(" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gangry.png" class="emote">
                                    <img title=":\" alt=":\" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gslash.png" class="emote">
                                    <img title="8) or B)" alt="8) or B)" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gcool.png" class="emote">
                                    <img title="gasm" alt="gasm" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Go.png" class="emote">
                                    <img title="kappa" alt="kappa" src="https://toka.io/assets/images/emotes/standard/chat/green/CHAT-Gkappa.png" class="emote">
                                </div>
                                <div>
                                    <span>Blue Chat Bubble Emote Set:</span>
                                    <img title=":)" alt=":)" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bsmile.png" class="emote">
                                    <img title=":D" alt=":D" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-BDsmile.png" class="emote">
                                    <img title=":P" alt=":P" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Btongue.png" class="emote">
                                    <img title=";)" alt=";)" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bwink.png" class="emote">
                                    <img title=":(" alt=":(" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bfrown2.png" class="emote">
                                    <img title="T_T" alt="T_T" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bcry.png" class="emote">
                                    <img title="-_-" alt="-_-" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-B-_-.png" class="emote">
                                    <img title=">:)" alt=">:)" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bevilsmile.png" class="emote">
                                    <img title=">:(" alt=">:(" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bangry.png" class="emote">
                                    <img title=":\" alt=":\" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bslash.png" class="emote">
                                    <img title="8) or B)" alt="8) or B)" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bcool.png" class="emote">
                                    <img title="gasm" alt="gasm" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bo.png" class="emote">
                                    <img title="kappa" alt="kappa" src="https://toka.io/assets/images/emotes/standard/chat/blue/CHAT-Bkappa.png" class="emote">
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-4" style="padding: 10px 15px !important;display:block;">4. How can I personalize my chatroom?</a>
                            </h4>
                        </div>
                        <div id="collapse-4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Personalization comes from editing your info page on your chatroom. We support the following markdown language:</p>
                                <ul>
                                    <li># Header 1 (Large)</li>
                                    <li>## Header 2 (Medium)</li>
                                    <li>### Header 3 (Small)</li>
                                    <li>-- Bullet</li>
                                    <li>**Bold**</li>
                                    <li>~~Strikethrough~~</li>
                                    <li>*Italics*</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-5" style="padding: 10px 15px !important;display:block;">5. What is a "hashtag" room?</a>
                            </h4>
                        </div>
                        <div id="collapse-5" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Hashtag rooms are temporary chatrooms that automatically get created by passing a chatroom id that does not exist after the /chatroom url. For example: <br /> <a href="/chatroom/hashtag">https://toka.io/chatroom/hashtag</a></p>
                                <p>These rooms are automatically created, and will consequently have no owner and can not be personalized for this reason.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-6" style="padding: 10px 15px !important;display:block;">6. What are the chat commands?</a>
                            </h4>
                        </div>
                        <div id="collapse-6" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>All users:</p>
                                <ul>
                                    <li>/spoiler [text] - Hides the text in a spoiler block. To view the message, users must click on the spoiler block.</li>
                                    <li>/me [text] - The message will be sent as you doing an action (i.e. "/me dances" -> "arc dances").</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-7" style="padding: 10px 15px !important;display:block;">7. How do I scroll in chat?</a>
                            </h4>
                        </div>
                        <div id="collapse-7" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>There's a few ways to scroll in chat.</p>
                                <ul>
                                    <li>You can drag the scroller on the scrollbar</li>
                                    <li>You can click on the chat, then press the up and down arrow keys</li>
                                    <li>You can scroll using the middle mouse button</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-8" style="padding: 10px 15px !important;display:block;">8. Future Plans (Updated 04/25/15)</a>
                            </h4>
                        </div>
                        <div id="collapse-8" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>TBD</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="site-footer">
            <?php // include_once("common/footer.php") ?>
        </section>
        <section id="site-forms">
            <?php include_once('form/site.php') ?>
        </section>
    </div>
</body>
</html>