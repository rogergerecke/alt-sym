This repro is only a backup from a website please dont use it.

# Installations Anleitung
Das System ist jetzt immer weiter gewachsen und darum braucht es 
jetzt schon ein kleines Handbuch für die erste Installation zum 
Start.  Sind diese erst einemal gemacht braucht es nichts mehr weiter.  

Aber ein Account bei https://symfony.com/blog/category/security-advisories 
ist zu empfehlen um über Sicherheitslücken informiert zu sein und diese in den 
meisten Fällen mit einem simplen compser update zu schließen.

Im Terminal ausführen wenn Composer Installiert ist.

`composer update`

## Grundsätzlich die .env
Systemeinstellung wie **Datenbank, Mailserver Verbindung, API Schlüßel** für externe Dienstleister werden jetzt alle in der **.env**  gemacht die **.env** ist die Grundkonfigurations Datei.

# Das Wetter widget
Da das Wetter-Plugin von Wetter .com noch nicht frei zu gestallten ist und an dieser API grade gearbeitet wird müssen wir erstmal wechseln damit das Wetter widget nicht das Mobile Layout stört.

Das Wetter widget wird jetzt über die API von https://openweathermap.org/api erzeugt!  Dafür brauch man dort ein Kostenlosen zugang weil dieser zugang kostenlos ist,  sind die anfragen dort limitiert es ist also nötig sie zu Cachen.

Weil Hetzner Ihr hoster ist haben Sie dort die möglichkeit einen Cronjob einzurichten unter Einstellung > Konfiguration > Cronjob Manager 

Auch bei diesen Cronjobs gibt es leider ein Limit deshalb müssen wir 4 Cronjobs anlegen das sich die Ausführungzeiten überschneiden und man einen Aufruf alle 30 Minuten hat.

#### Cronjob-Einstellung:

`Interpreter: Wget`

`Skript (absoluter Pfad): --spider https://altmuehlsee.com/open/weather/api`

#### .env Settings
`OPENWEATHER_LAT_LON=@49.1161974,10.6974247`

`OPENWEATHER_API_KEY=youre api key here`

`OPENWEATHER_API_TYPE=onecall`

# TO PRODUCTION

Create the real **.env.php** for better performance.

# SETUP

` yarn encore production`

Database Conection

SMTP Conection

.entrypoints.json dosnt exist use

`yarn build`