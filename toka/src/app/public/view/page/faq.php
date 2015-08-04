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
                                <a style="padding: 10px 15px !important;display:block;">2. What can I do on Toka?</a>
                            </h4>
                        </div>
                        <div>
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
                                <a style="padding: 10px 15px !important;display:block;">3. What emotes does Toka have?</a>
                            </h4>
                        </div>
                        <div>
                            <div class="panel-body">
                                <p>We have a 3 sets of standard emotes, right now we are rotating with the cat set. The emote's text code shows if you hover over them, use this to display the emote in chat.</p>
                                <div>
                                    <span>Universal Emote Set:</span>
                                    <img title="o/" alt="o/" src="/assets/images/emotes/toka.png" class="emote">
                                    <img title="<3" alt="<3" src="/assets/images/emotes/heart.png" class="emote">
                                </div>
                                <div>
                                    <span>Cat Emote Set:</span>
                                    <img title=":)" alt=":)" src="/assets/images/emotes/standard/cat/10.png" class="emote">
                                    <img title=":D" alt=":D" src="/assets/images/emotes/standard/cat/11.png" class="emote">
                                    <img title=":P" alt=":P" src="/assets/images/emotes/standard/cat/8.png" class="emote">
                                    <img title=";)" alt=";)" src="/assets/images/emotes/standard/cat/13.png" class="emote">
                                    <img title=":(" alt=":(" src="/assets/images/emotes/standard/cat/12.png" class="emote">
                                    <img title="T_T" alt="T_T" src="/assets/images/emotes/standard/cat/4.png" class="emote">
                                    <img title="-_-" alt="-_-" src="/assets/images/emotes/standard/cat/0.png" class="emote">
                                    <img title=">:)" alt=">:)" src="/assets/images/emotes/standard/cat/5.png" class="emote">
                                    <img title=">:(" alt=">:(" src="/assets/images/emotes/standard/cat/1.png" class="emote">
                                    <img title=":\" alt=":\" src="/assets/images/emotes/standard/cat/9.png" class="emote">
                                    <img title="8) or B)" alt="8) or B)" src="/assets/images/emotes/standard/cat/3.png" class="emote">
                                    <img title="catGasm" alt="catGasm" src="/assets/images/emotes/standard/cat/7.png" class="emote">
                                    <img title="catpa" alt="catpa" src="/assets/images/emotes/standard/cat/6.png" class="emote">
                                </div>
                                <div>
                                    <span>White Chat Bubble Emote Set:</span>
                                    <img title=":)" alt=":)" src="/assets/images/emotes/standard/chat/white/10.png" class="emote">
                                    <img title=":D" alt=":D" src="/assets/images/emotes/standard/chat/white/11.png" class="emote">
                                    <img title=":P" alt=":P" src="/assets/images/emotes/standard/chat/white/8.png" class="emote">
                                    <img title=";)" alt=";)" src="/assets/images/emotes/standard/chat/white/13.png" class="emote">
                                    <img title=":(" alt=":(" src="/assets/images/emotes/standard/chat/white/12.png" class="emote">
                                    <img title="T_T" alt="T_T" src="/assets/images/emotes/standard/chat/white/4.png" class="emote">
                                    <img title="-_-" alt="-_-" src="/assets/images/emotes/standard/chat/white/0.png" class="emote">
                                    <img title=">:)" alt=">:)" src="/assets/images/emotes/standard/chat/white/5.png" class="emote">
                                    <img title=">:(" alt=">:(" src="/assets/images/emotes/standard/chat/white/1.png" class="emote">
                                    <img title=":\" alt=":\" src="/assets/images/emotes/standard/chat/white/9.png" class="emote">
                                    <img title="8) or B)" alt="8) or B)" src="/assets/images/emotes/standard/chat/white/3.png" class="emote">
                                    <img title="gasm" alt="gasm" src="/assets/images/emotes/standard/chat/white/7.png" class="emote">
                                    <img title="kappa" alt="kappa" src="/assets/images/emotes/standard/chat/white/6.png" class="emote">
                                </div>
                                <div>
                                    <span>Green Chat Bubble Emote Set:</span>
                                    <img title=":)" alt=":)" src="/assets/images/emotes/standard/chat/green/10.png" class="emote">
                                    <img title=":D" alt=":D" src="/assets/images/emotes/standard/chat/green/11.png" class="emote">
                                    <img title=":P" alt=":P" src="/assets/images/emotes/standard/chat/green/8.png" class="emote">
                                    <img title=";)" alt=";)" src="/assets/images/emotes/standard/chat/green/13.png" class="emote">
                                    <img title=":(" alt=":(" src="/assets/images/emotes/standard/chat/green/12.png" class="emote">
                                    <img title="T_T" alt="T_T" src="/assets/images/emotes/standard/chat/green/4.png" class="emote">
                                    <img title="-_-" alt="-_-" src="/assets/images/emotes/standard/chat/green/0.png" class="emote">
                                    <img title=">:)" alt=">:)" src="/assets/images/emotes/standard/chat/green/5.png" class="emote">
                                    <img title=">:(" alt=">:(" src="/assets/images/emotes/standard/chat/green/1.png" class="emote">
                                    <img title=":\" alt=":\" src="/assets/images/emotes/standard/chat/green/9.png" class="emote">
                                    <img title="8) or B)" alt="8) or B)" src="/assets/images/emotes/standard/chat/green/3.png" class="emote">
                                    <img title="gasm" alt="gasm" src="/assets/images/emotes/standard/chat/green/7.png" class="emote">
                                    <img title="kappa" alt="kappa" src="/assets/images/emotes/standard/chat/green/6.png" class="emote">
                                </div>
                                <div>
                                    <span>Blue Chat Bubble Emote Set:</span>
                                    <img title=":)" alt=":)" src="/assets/images/emotes/standard/chat/blue/10.png" class="emote">
                                    <img title=":D" alt=":D" src="/assets/images/emotes/standard/chat/blue/11.png" class="emote">
                                    <img title=":P" alt=":P" src="/assets/images/emotes/standard/chat/blue/8.png" class="emote">
                                    <img title=";)" alt=";)" src="/assets/images/emotes/standard/chat/blue/13.png" class="emote">
                                    <img title=":(" alt=":(" src="/assets/images/emotes/standard/chat/blue/12.png" class="emote">
                                    <img title="T_T" alt="T_T" src="/assets/images/emotes/standard/chat/blue/4.png" class="emote">
                                    <img title="-_-" alt="-_-" src="/assets/images/emotes/standard/chat/blue/0.png" class="emote">
                                    <img title=">:)" alt=">:)" src="/assets/images/emotes/standard/chat/blue/5.png" class="emote">
                                    <img title=">:(" alt=">:(" src="/assets/images/emotes/standard/chat/blue/1.png" class="emote">
                                    <img title=":\" alt=":\" src="/assets/images/emotes/standard/chat/blue/9.png" class="emote">
                                    <img title="8) or B)" alt="8) or B)" src="/assets/images/emotes/standard/chat/blue/3.png" class="emote">
                                    <img title="gasm" alt="gasm" src="/assets/images/emotes/standard/chat/blue/7.png" class="emote">
                                    <img title="kappa" alt="kappa" src="/assets/images/emotes/standard/chat/blue/6.png" class="emote">
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a style="padding: 10px 15px !important;display:block;">4. How can I personalize my chatroom?</a>
                            </h4>
                        </div>
                        <div>
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
                                <a style="padding: 10px 15px !important;display:block;">5. What is a "hashtag" room?</a>
                            </h4>
                        </div>
                        <div>
                            <div class="panel-body">
                                <p>Hashtag rooms are temporary chatrooms that automatically get created by passing a chatroom id that does not exist after the /chatroom url. For example: <br /> <a href="/chatroom/hashtag">/chatroom/hashtag</a></p>
                                <p>These rooms are automatically created, and will consequently have no owner and can not be personalized for this reason.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0px !important;">
                            <h4 class="panel-title">
                                <a style="padding: 10px 15px !important;display:block;">6. What are the chat commands?</a>
                            </h4>
                        </div>
                        <div>
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
                                <a style="padding: 10px 15px !important;display:block;">7. How do I scroll in chat?</a>
                            </h4>
                        </div>
                        <div>
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
                </div>
            </div>
        </section>
        <section id="site-modals">
            <?php include_once('form/site.php') ?>
        </section>
    </div>
</body>
</html>