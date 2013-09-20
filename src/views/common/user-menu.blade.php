<div class="nav-fixed-topright" style="visibility: hidden">
    <ul class="nav nav-user-menu">
        <li class="user-sub-menu-container">
            <a href="javascript:;">
                <i class="user-icon"></i><span class="nav-user-selection">Theme Options</span><i class="icon-menu-arrow"></i>
            </a>
            <ul class="nav user-sub-menu">
                <li class="light">
                    <a href="javascript:;">
                        <i class='icon-photon stop'></i>Light Version
                    </a>
                </li>
                <li class="dark">
                    <a href="javascript:;">
                        <i class='icon-photon stop'></i>Dark Version
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a id="user-mail" href="javascript:;">
                <i class="icon-photon mail"></i>
            </a>
        </li>
        <li>
            <a id="user-notifications" href="javascript:;">
                <i class="icon-photon comment_alt2_stroke"></i>
                <div class="notification-count">7</div>
            </a>
        </li>
    </ul>
</div>

<div class="user-mail-template">
    <ul class="notification-list">
        <li>
            <div class="avatar-image">
                <img src="images/photon/user1.jpg" alt="profile"/>
            </div>
            <span>Nunc Cenenatis:</span> 
            <span class="mail-content">Hi, can you meet me at the office tomorrow morning?</span>
            <div class="time">3 mins ago</div>
        </li>
        <li>
            <div class="avatar-image">
                <img src="images/photon/user2.jpg" alt="profile"/>
            </div>
            <span>Prasent Neque:</span> 
            <span class="mail-content">Just a quick question: do you know the balance on the adsense account?</span>
            <div class="time">17 mins ago</div>
        </li>
        <li>
            <div class="avatar-image">
                <img src="images/photon/user3.jpg" alt="profile"/>
            </div>
            <span>Flor Demoa:</span> 
            <span class="mail-content">Hey, we're going surfing tomorrow. Feel free to join in.</span>
            <div class="time">3 hrs ago</div>
        </li>
    </ul>
</div>

<div class="user-note-template">
    <ul class="notification-list">
        <li>
            <div class="avatar-image">
                <img src="images/photon/user2.jpg" alt="profile"/>
            </div>
            <span>Nunc Cenenatis</span> 
            <span class="mail-content">likes your website.</span>
            <div class="time">32 mins ago</div>
        </li>
        <li>
            <div class="avatar-image">
                <img src="images/photon/user4.jpg" alt="profile"/>
            </div>
            <span>Nunc Neque</span> 
            <span class="mail-content">wrote a new post.</span>
            <div class="time">57 mins ago</div>
        </li>
        <li>
            <div class="avatar-image">
                <img src="images/photon/user3.jpg" alt="profile"/>
            </div>
            <span>Flor Demoa</span> 
            <span class="mail-content">submitted a new ticket.</span>
            <div class="time">1.5 hrs ago</div>
        </li>
        <li>
            <div class="avatar-image">
                <img src="images/photon/user1.jpg" alt="profile"/>
            </div>
            <span>Nunc Cenenatis</span> 
            <span class="mail-content">wrote a new post.</span>
            <div class="time">3 hrs ago</div>
        </li>
        <li>
            <div class="avatar-image">
                <img src="images/photon/user4.jpg" alt="profile"/>
            </div>
            <span>Nunc Neque</span> 
            <span class="mail-content">wrote a new post.</span>
            <div class="time">3.5 mins ago</div>
        </li>
        <li>
            <div class="avatar-image">
                <img src="images/photon/user3.jpg" alt="profile"/>
            </div>
            <span>Flor Demoa</span> 
            <span class="mail-content">likes your website.</span>
            <div class="time">3.5 hrs ago</div>
        </li>
        <li>
            <div class="avatar-image">
                <img src="images/photon/user1.jpg" alt="profile"/>
            </div>
            <span>Nunc Cenenatis</span> 
            <span class="mail-content">wrote a new post.</span>
            <div class="time">4 hrs ago</div>
        </li>
    </ul>
</div>

<script>
    $(function(){
        setTimeout(function(){
            $('.nav-fixed-topright').removeAttr('style');
        }, 300);
        
        $(window).scroll(function(){
            if($('.breadcrumb-container').length){
                var scrollState = $(window).scrollTop();
                if (scrollState > 0) $('.nav-fixed-topright').addClass('nav-released');
                else $('.nav-fixed-topright').removeClass('nav-released')
            }
        });
        $('.user-sub-menu-container').on('click', function(){
            $.pnotify_remove_all();
            $(this).toggleClass('active-user-menu');
        });
        $('.user-sub-menu .light').on('click', function(){
            if ($('body').is('.light-version')) return;
            $('body').addClass('light-version');
            setTimeout(function() {
                $.cookie('themeColor', 'light', {
                    expires: 7,
                    path: '/'
                });
            }, 500);
        });
        $('.user-sub-menu .dark').on('click', function(){
            if ($('body').is('.light-version')) {
                $('body').removeClass('light-version');
                $.cookie('themeColor', 'dark', {
                    expires: 7,
                    path: '/'
                });
            }
        });

        // MAIL AND NOTIFICATIONS
        var notificationOpen = false;
        var mailOpen = false;
        var notificationTimeout = false;
        $('#user-mail').on('click', function(){
            if (notificationTimeout) return;
            notificationTimeout = !notificationTimeout;
            setTimeout(function() {
                notificationTimeout = !notificationTimeout;
            }, 300);

            // console.log($('.user-mail-template').html());
            if (!mailOpen){
                var miniTimeout = 0;
                if(notificationOpen) {
                    $.pnotify_remove_all();
                    var miniTimeout = 200;
                }
                $('.active-user-menu').removeClass('active-user-menu');
                setTimeout(function() {
                    $.pnotify({
                        title: 'Messages',
                        text: $('.user-mail-template').html(),
                        type: 'info',
                        html: true,
                        hide: false,
                        icon: false,
                        sticker: false,
                        animate_speed: 100,
                        addclass: 'user-note',
                        before_open: function (pnotify) {
                            pnotify.find('br').remove();
                        },
                        before_close: function () {
                            if(mailOpen)
                                mailOpen = !mailOpen;
                        }
                    });
                    mailOpen = !mailOpen;
                }, miniTimeout);
            }
            else{
                $.pnotify_remove_all();
            }
        });

        $('#user-notifications').on('click', function(){
            if (notificationTimeout) return;
            notificationTimeout = !notificationTimeout;
            setTimeout(function() {
                notificationTimeout = !notificationTimeout;
            }, 300);

            // console.log($('.user-mail-template').html());
            if (!notificationOpen){
                var miniTimeout = 0;
                if(mailOpen) {
                    $.pnotify_remove_all();
                    var miniTimeout = 200;
                };
                $('.active-user-menu').removeClass('active-user-menu');
                setTimeout(function() {
                    $.pnotify({
                        title: 'Notifications',
                        text: $('.user-note-template').html(),
                        type: 'info',
                        html: true,
                        hide: false,
                        icon: false,
                        sticker: false,
                        animate_speed: 100,
                        addclass: 'user-note',
                        before_open: function (pnotify) {
                            pnotify.find('br').remove();
                        },
                        before_close: function () {
                            if(notificationOpen)
                                notificationOpen = !notificationOpen;
                        }
                    });
                    notificationOpen = !notificationOpen;
                }, miniTimeout);
            }
            else{
                $.pnotify_remove_all();
            }
        });
    });
</script>

