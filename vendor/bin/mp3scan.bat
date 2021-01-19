@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../wapmorgan/MP3Info/bin/mp3scan
php "%BIN_TARGET%" %*
