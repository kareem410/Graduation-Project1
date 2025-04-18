@echo off
title Laravel + Ngrok Auto Launcher

echo ✅ Starting Laravel Server...
start cmd /k "C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe artisan serve"

timeout /t 5 > nul

echo ✅ Starting Ngrok...
start cmd /k "E:\Ngrok\ngrok.exe http 8000"
