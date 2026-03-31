<?php
class VueFeed extends VueBase {
    public function __construct() {
        parent::__construct("Home - LinkUp");
        $this->actionActive = 'feed';
    }

    public function afficher() {
        global $trad; 

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/feed.css"> <div class="explore--content">

            <div class="feed">
                <div class="feed--nav">
                    <input type="button" value="For you" class="feed--nav--button">
                    <input type="button" value="Following" class="feed--nav--button">
                </div>
                
                <div class="posts--container">
                    <div class="post">
                        <div class="post--content">
                            <div class="post--header">
                                <img src="img/ppWoman.png" alt="pp" class="post--header--img" />
                                <div class="post--header--text">
                                    <span class="card--username Inter">Sophie Martin</span>
                                    <span class="card--userID">@sophiemartin 2h ago</span>
                                    <div class="post--text Inter">
                                        Hosting a live jazz piano session tonight at 8 PM! Everyone welcome 🎹
                                    </div>
                                </div>
                            </div>
                            <div class="post--media">
                                <img src="img/post.png" alt="postImg" class="post--img">
                            </div>
                        </div>
                        <div class="post--footer">
                            <ul class="tag--list">
                                <li class="post--tag Inter">#Jazz</li>
                                <li class="post--tag Inter">#Piano</li>
                                <li class="post--tag Inter">#Live</li>
                            </ul>
                            <span class="post--location Inter"><img src="img/pin.png" alt="pin image"> Paris, France</span>
                            <div class="post--actions">
                                <div class="post--actions--left">
                                    <div class="post--comment"><img src="img/commentIcon.png" alt="comment" id="post--comment--icon">12</div>
                                    <div class="post--event"><img src="img/calendarIcon.png" alt="calendar" id="post--calendar--icon">Join event</div>
                                </div>
                                <div class="post--actions--right">
                                    <img src="img/dots.png" alt="dots" class="post--dots">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="post">
                        <div class="post--content">
                            <div class="post--header">
                                <img src="img/ppMan.png" alt="pp" class="post--header--img" />
                                <div class="post--header--text">
                                    <span class="card--username Inter">Robert Chen</span>
                                    <span class="card--userID">@robertc 5h ago</span>
                                    <div class="post--text Inter">
                                        Looking for vinyl enthusiasts to share collection stories. Coffee meetup this Saturday!
                                    </div>
                                </div>
                            </div>
                            <div class="post--media"></div>
                        </div>
                        <div class="post--footer">
                            <ul class="tag--list">
                                <li class="post--tag Inter">#Vinyl</li>
                                <li class="post--tag Inter">#Collectors</li>
                                <li class="post--tag Inter">#Meetup</li>
                            </ul>
                            <span class="post--location Inter"><img src="img/pin.png" alt="pin image"> Lyon, France</span>
                            <div class="post--actions">
                                <div class="post--actions--left">
                                    <div class="post--comment"><img src="img/commentIcon.png" alt="comment" id="post--comment--icon">12</div>
                                    <div class="post--event"><img src="img/calendarIcon.png" alt="calendar" id="post--calendar--icon">Join event</div>
                                </div>
                                <div class="post--actions--right">
                                    <img src="img/dots.png" alt="dots" class="post--dots">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="post">
                        <div class="post--content">
                            <div class="post--header">
                                <img src="img/ppWoman_second.png" alt="pp" class="post--header--img" />
                                <div class="post--header--text">
                                    <span class="card--username Inter">Emma Williams</span>
                                    <span class="card--userID">@emmaw 1d ago</span>
                                    <div class="post--text Inter">
                                        Starting a beginner guitar workshop next week. Intergenerational learning encouraged! 🎸
                                    </div>
                                </div>
                            </div>
                            <div class="post--media">
                                <img src="img/post.png" alt="postImg" class="post--img">
                            </div>
                        </div>
                        <div class="post--footer">
                            <ul class="tag--list">
                                <li class="post--tag Inter">#Guitar</li>
                                <li class="post--tag Inter">#Workshop</li>
                                <li class="post--tag Inter">#Beginners</li>
                            </ul>
                            <span class="post--location Inter"><img src="img/pin.png" alt="pin image"> Marseille, France</span>
                            <div class="post--actions">
                                <div class="post--actions--left">
                                    <div class="post--comment"><img src="img/commentIcon.png" alt="comment" id="post--comment--icon">12</div>
                                    <div class="post--event"><img src="img/calendarIcon.png" alt="calendar" id="post--calendar--icon">Join event</div>
                                </div>
                                <div class="post--actions--right">
                                    <img src="img/dots.png" alt="dots" class="post--dots">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidePanel">
                <section class="suggestions">
                    <div class="suggestion">
                        <div class="suggestion--left">
                            <img src="img/ppSuggestion.png" alt="PP" class="suggestion--pp">
                            <div class="suggestion--content">
                                <span class="card--username Inter">Marie Dubois</span>
                                <span class="card--userID Inter">@mariedubois</span>
                                <ul class="card--language--list ">
                                    <li class="card--language Inter">#Jazz</li>
                                    <li class="card--language Inter">#Vocal</li>
                                </ul>
                                <span class="post--location Inter"><img src="img/pin.png" alt="pin image"> Marseille, France</span>
                            </div>
                        </div>
                        <div class="suggestion--button--container">
                            <input type="button" value="connect" class="suggestion--button ">
                        </div>
                    </div>

                    <div class="suggestion">
                        <div class="suggestion--left">
                            <img src="img/ppMan_second.png" alt="PP" class="suggestion--pp">
                            <div class="suggestion--content">
                                <span class="card--username Inter">Jean Laurent</span>
                                <span class="card--userID Inter">@jeanlaurent</span>
                                <ul class="card--language--list">
                                    <li class="card--language Inter">#Piano</li>
                                    <li class="card--language Inter">#Classical</li>
                                </ul>
                                <span class="post--location Inter"><img src="img/pin.png" alt="pin image"> Lyon, France</span>
                            </div>
                        </div>
                        <div class="suggestion--button--container">
                            <input type="button" value="connect" class="suggestion--button">
                        </div>
                    </div>
                </section>

                <section class="trending">
                    <h3 class="Inter">Trending boards</h3>
                    <ul class="trending--list">
                        <li class="trending--item">
                            <div class="trending--content">
                                <span class="trending--label Inter">Trending</span>
                                <span class="trending--hashtag Inter">#Piano</span>
                                <span class="trending--posts Inter">5155 posts</span>
                            </div>
                            <img src="img/musicIcon.png" alt="music" class="trending--icon">
                        </li>
                        <li class="trending--item">
                            <div class="trending--content">
                                <span class="trending--label Inter">Trending</span>
                                <span class="trending--hashtag Inter">#Vinyl</span>
                                <span class="trending--posts Inter">1053 posts</span>
                            </div>
                            <img src="img/musicIcon.png" alt="music" class="trending--icon">
                        </li>
                        <li class="trending--item">
                            <div class="trending--content">
                                <span class="trending--label Inter">Trending</span>
                                <span class="trending--hashtag Inter">#CM</span>
                                <span class="trending--posts Inter">6778 posts</span>
                            </div>
                            <img src="img/musicIcon.png" alt="music" class="trending--icon">
                        </li>
                        <li class="trending--item">
                            <div class="trending--content">
                                <span class="trending--label Inter">Trending</span>
                                <span class="trending--hashtag Inter">#Guitar</span>
                                <span class="trending--posts Inter">8104 posts</span>
                            </div>
                            <img src="img/musicIcon.png" alt="music" class="trending--icon">
                        </li>
                    </ul>
                </section>
            </div>
        </div>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}