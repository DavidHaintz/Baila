Die Idee ist mir gekommen, als Atrax mit dem Webpanel vom Liphyria Bot aufgetaucht ist. Ein Webpanel für alle.
Mit dem Projekt Baila habe ich das nun realisiert und ein sicheres Webpanel mit einfacher Erweiterungsmöglichkeit geschrieben. Ein paar XSS sind zwar noch möglich, doch das nur vom Adminpanel aus. Und wer schon einmal da drin ist, wird keine XSS-Attacke starten. Aber natürlich werden die auch bald gefixt.

**Features:**
- Integriertes IP2Country
- Taskmanagement
- Usermanagement
- Bots können einen User zugeordnet werden
- 3 Berechtigungsstufen
- Debug-Modul (ermöglicht einfachere Auswertung der Fehler auf Remote-PCs)
- Erweiterbar durch Module

**Security:**
- Verwendet prepared Statements für Datenbankabfragen (schnell & sicher)
- Kodiert alle von außen kommenden Daten (keine XSS)


# Screenshots
Login:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/login.PNG "Login")


Stats Infections:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/stats1.PNG "Stats Infections")


Stats Countries:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/stats2.PNG "Stats Countries")


Stats OSes:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/stats3.PNG "Stats OSes")


Stats Online/Offline:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/stats4.PNG "Stats Online/Offline")


Bots:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/bots.PNG "Bots")


Tasks overview:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/tasks1.PNG "Tasks overview")


Create task:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/tasks2.PNG "Create task")


Users overview:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/users1.PNG "Users overview")


Create user:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/users2.PNG "Create user")


Debug:
![alt text](https://raw2.github.com/IRET0x00/Baila/master/screens/debug.PNG "Debug")



# Developer Note

Task-Aufruf: gate.php?hwid=botHWID&os=botOS&pwd=123456
Plugin-Aufruf: gate.php?hwid=botHWID&os=botOS&pwd=123456&plugin-param=bla
Debug-Aufruf: gate.php?err=Error&os=botOS&pwd=123456