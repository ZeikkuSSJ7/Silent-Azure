<div class="player">
    <div class="progress"></div>
    <div class="container">
        <div class="wrapper-cover-song-album" <?php if ($isMobile) echo 'style="width: 100%;"' ?>>
            <div class="box" <?php if ($isMobile) echo 'style="display: block;"' ?>>
                <div class="box" style="display: inline-block;">
                    <img class="cover" style="margin: 0 10px 0 0; float: left;">
                    <div style="float: right; display: flex; align-items: center; height: 100%;  <?php if ($isMobile) echo "width: 30px; height: 30px;"; ?>">
                        <button class="like" style="background-size: 25px;"></button>
                    </div>
                    <div class="song-album flex-align" <?php if ($isMobile) echo 'style="height: 100%; margin: 0; padding: 5px;"'; else echo 'style="display: flex; align-items: center; padding: 0px"' ?>>
                        <div style="flex: 1; min-width: 0px; <?php if ($isMobile) echo "height: 100%;"; ?>">
                            <div class="song" <?php if ($isMobile) echo 'style="margin: 3px 0; font-size: 12px"'; else echo 'style="margin: 3px 0; font-size: 18px;"'; ?>></div>
                            <div class="album" <?php if ($isMobile) echo 'style="color: #222222; font-size: 70%"'; else echo 'style="color: #222222;"'; ?>></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="controls">
            <div class="box" <?php if ($isMobile) echo 'style="height: 60%;"' ?>>
                <div <?php if ($isMobile) echo 'style="width: 100%; height: 100%; display: inline-flex; margin: auto;"'; else echo 'style="display: inline-flex; margin: auto;"' ?>>
                    <button class="no-loop"></button>
                    <button class="previous"></button>
                    <button class="play-button" <?php if ($isMobile) echo 'style="width: 40px;"'; else echo 'style="width: 60px; height: 60px;"'; ?>></button>
                    <button class="next"></button>
                    <button class="random"></button>
                </div>
            </div>
        </div>
        <div class="wrapper-timer-volume">
            <div class="box" style="display: block; float: right;">
                <div class="flex-align timer-text" <?php if ($isMobile) echo 'style="font-size: 11px; margin: 0px; padding: 3px"'; else echo 'style="font-size: 11px; margin: 0px;"' ?>>
                    00:00 / 00:00
                </div>
                <div class="volume-container" style="padding: 0px;">
                    <div class="flex-align"  <?php if ($isMobile) echo 'style="float: right; display: inline-flex; width: 100%; margin: 0px; padding: 0px;"'; else echo 'style="float: right; display: inline-flex;"'; ?>>
                        <div class="vol-min"></div>
                        <div class="flex-align">
                            <div class="volume" style="border: 1px solid black;"></div>
                        </div>
                        <div class="vol-max"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<audio class="real-audio" preload src="<?php echo $hostLink . '/' . $firstSong ?>"></audio>
</div>