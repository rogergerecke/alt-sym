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

# Kernkomponenten
Da man zum Glück das Rad nicht neu erfinden muss nutzen wir z.b. für die Datenbank eine leistungsstarke 
Komponente https://www.doctrine-project.org/ . Gern können Sie hier die Seiten der Kernkomponenten besuchen 
und Wünsche äußern über weiter Funktionen die diese zur verfügung stellen sollte ich diese nicht integriert haben.

- [Doctrine](https://www.doctrine-project.org/)
- [Symfony](https://symfony.com/doc/current/components/index.html)
- [CKEditor](https://ckeditor.com/ckeditor-5/)
- [Image-editor](https://ui.toast.com/tui-image-editor/)
- [OpenWeather](https://openweathermap.org/api)


# Fehler melden !

**Hier** [issues](https://github.com/rogergerecke/alt-sym/issues) **melden und eintragen.**

---

### END

---

###Update Change log

- Create first branch 1.1.10 contain
- Update Weather Api Connection
- Update from the Hostel Data source
- Docker Curl downgrade to the version from Hetzner

```
###### TO PRODUCTION Gedanken stütze für mich.
Notizen für mich wenn es in die Public Production geht.

Remove header meta no robots index

Create the real **.env.php** for better performance.

###### SETUP

` yarn encore production`

Database Conection

SMTP Conection

.entrypoints.json dosnt exist use

`yarn build`

Curl Bug must fix on Server:
Attention Curl 7.64.0 dosnt work contain a bug in buffer
Hetzner run Curl 7.52.1
