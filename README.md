Die Idee ist mir gekommen, als Atrax mit dem Webpanel vom Liphyria Bot aufgetaucht ist. Ein Webpanel f�r alle.
Mit dem Projekt Baila habe ich das nun realisiert und ein sicheres Webpanel mit einfacher Erweiterungsm�glichkeit geschrieben.

Ein paar XSS sind zwar noch m�glich, doch das nur vom Adminpanel aus. Und wer schon einmal da drin ist, wird keine XSS-Attacke starten. Aber nat�rlich werden die auch bald gefixt.

**Features:**
- Integriertes IP2Country
- Taskmanagement
- Usermanagement
- Bots k�nnen einen User zugeordnet werden
- 3 Berechtigungsstufen
- Debug-Modul (erm�glicht einfachere Auswertung der Fehler auf Remote-PCs)
- Erweiterbar durch Module

**Security:**
- Verwendet prepared Statements f�r Datenbankabfragen (schnell & sicher)
- Kodiert alle von au�en kommenden Daten (keine XSS)


# Screenshots
Login:
![alt text](http://abload.de/img/login2qsr1.png "Login")


Stats Infections:
![alt text](http://abload.de/img/stats_infectionsg7s89.png "Stats Infections")


Stats Countries:
![alt text](http://abload.de/img/stats_countriesiasci.png "Stats Countries")


Stats OSes:
![alt text](http://abload.de/img/stats_os8gs6n.png "Stats OSes")


Stats Online/Offline:
![alt text](http://abload.de/img/stats_onoff97smg.png "Stats Online/Offline")


Bots:
![alt text](http://abload.de/img/botsbtsge.png "Bots")


Tasks overview:
![alt text](http://abload.de/img/taskspzsw7.png "Tasks overview")


Edit task:
![alt text](http://abload.de/img/tasks_editjks72.png "Create task")


Debug:
![alt text](http://abload.de/img/debugkashy.png "Debug")


Users overview:
![alt text](http://abload.de/img/usersmmsj8.png "Users overview")


Edit user:
![alt text](http://abload.de/img/users_editfts4t.png "Create user")


Settings overview:
![alt text](http://abload.de/img/settings5is4p.png "Settings overview")


Edit setting:
![alt text](http://abload.de/img/settings_edit05sdp.png "Edit setting")


Changelog:
![alt text](http://abload.de/img/changelog87sc3.png "Changelog")
