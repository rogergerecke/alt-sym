This repro is only a backup from a website please don't use it.

# Installations Anleitung
Das System ist jetzt immer weiter gewachsen und darum braucht es 
jetzt schon ein kleines Handbuch für die erste Installation zum 
Start.  Sind diese erst einmal gemacht, braucht es nichts mehr weiter.  

Aber ein Account bei https://symfony.com/blog/category/security-advisories 
ist zu empfehlen um,  über Sicherheitslücken informiert zu sein und diese in den 
meisten Fällen mit einem simplen Composer update zu schließen.

Im Terminal ausführen,  wenn Composer installiert ist.

`composer update`

## Grundsätzlich die .env
Systemeinstellung wie **Datenbank, Mailserver Verbindung, API Schlüßel** für externe Dienstleister werden jetzt alle in der **.env**  gemacht die **.env** ist die Grundkonfigurations Datei.

# Das Wetter widget
Da das Wetter-Plugin von Wetter .com noch nicht frei zu gestallten ist und an dieser API grade gearbeitet wird müssen wir,  erstmal wechseln damit das Wetter widget nicht das Mobile Layout stört.

Das Wetter widget wird jetzt über die API von https://openweathermap.org/api erzeugt!  Dafür brauch man dort ein Kostenlosen zugang weil dieser zugang kostenlos ist,  sind die anfragen dort limitiert es ist also nötig sie zu Cachen.

Weil Hetzner Ihr hoster ist haben Sie dort die möglichkeit einen Cronjob einzurichten unter Einstellung > Konfiguration > Cronjob Manager 

Auch bei diesen Cronjobs gibt es leider ein Limit deshalb müssen wir 4 Cronjobs anlegen das sich die Ausführungzeiten überschneiden und man einen Aufruf alle 30 Minuten hat.

#### Cronjob-Einstellung fürs Wetter Update:

`Interpreter: Wget`

`Skript (absoluter Pfad): --spider https://altmuehlsee.com/open/weather/api`

#### .env Settings fürs Wetter:
`OPENWEATHER_LAT_LON=@49.1161974,10.6974247`

`OPENWEATHER_API_KEY=youre api key here`

`OPENWEATHER_API_TYPE=onecall`

# Der Admin- und Member Bereich

Der Admin und die Benutzer haben einen Login: **/login** die Inhalte werden durch unterschiedliche Benutzer Rechte ausgespielt

# Kernkomponenten
Da man zum Glück das Rad nicht neu erfinden muss nutzen wir z.b. für die Datenbank eine leistungsstarke 
Komponente https://www.doctrine-project.org/ . Gern können Sie hier die Seiten der Kernkomponenten besuchen 
und Wünsche äußern über weiter Funktionen die diese zur verfügung stellen sollte ich diese nicht integriert haben.

- [Doctrine](https://www.doctrine-project.org/)
- [Symfony](https://symfony.com/doc/current/components/index.html)
- [CKEditor](https://ckeditor.com/ckeditor-5/)
- [Image-editor](https://ui.toast.com/tui-image-editor/)
- [OpenWeather](https://openweathermap.org/api)

# Fahrplan
## (18.04.20)
~~1. Mobil Bootstrap 4 Design~~

~~2. Anbindung OpenWeather API Cronjob~~

## (21.04.20)

**3.** Admin Bereich

**4.** Statische Seiten

**5.** CKEditor 4 Inhaltsbearbeitung im Admin

## (17.05.20)
**6.** Checkout

**7.** Member Aria 

## (31.05.20)
**8.** Integration https://ui.toast.com/tui-image-editor/

## August
**iCalendar** Kalender integration für Veranstalltungen **im - und export**

## Deadline 01.10.20



# Fehler melden !

**Hier** [issues](https://github.com/rogergerecke/alt-sym/issues) **melden und eintragen.**

---

### Update Change Log

### Branch: 1.1.11 
- Update Hostel Data structure


### Branch: 1.1.10
- Add Weather Widget Mobile Style
- Add new Hostel Search Design

### Bugs
- Symfony with Curl 7.64 don't work correct Curl have a bug.


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
```

CKFinder
NOTE: Since usually setting permissions to 0777 is insecure, it is advisable to change the group ownership of the directory to the same user as Apache and add group write permissions instead. Please contact your system administrator in case of any doubts.
