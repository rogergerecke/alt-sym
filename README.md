**This repro is only a backup from a website please don't use it.**

# [Das Wetter widget](#weatherwidget)
Nur für das Wetter Plugin wird ein zugang bei https://openweathermap.org/ benötigt dieser ist kostenlos

Das Wetter widget wird jetzt über die API von https://openweathermap.org/api erzeugt!  Dafür brauch man dort ein Kostenlosen zugang weil dieser zugang kostenlos ist,  sind die anfragen dort limitiert es ist also nötig sie zu Cachen.

Weil Hetzner Ihr hoster ist haben Sie dort die möglichkeit einen Cronjob einzurichten unter Einstellung > Konfiguration > Cronjob Manager 

Auch bei diesen Cronjobs gibt es leider ein Limit deshalb müssen wir 4 Cronjobs anlegen das sich die Ausführungzeiten überschneiden und man einen Aufruf alle 30 Minuten hat.

#### Cronjob-Einstellung fürs Wetter Update:

`Interpreter: Wget`

`Skript (absoluter Pfad): --spider https://altmuehlsee.com/open/weather/api`



#### Project status

## Juli
**iCalendar** Kalender integration für Veranstalltungen **im - und export**
- Abgreifen von Events und Veranstalltung mit klick in Kalender übernehmen

Fertig am 29.07.2020

Attention update to the new symfony mail componente

# Hier Fehler melden !

**Hier** [issues](https://github.com/rogergerecke/alt-sym/issues) **melden und eintragen.**

---

##### Developer Info

BUG - Symfony with Curl 7.64 don't work correct Curl have a bug.

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

[https://studio-42.github.io/elFinder/#elf_l1_Lw]: https://studio-42.github.io/elFinder/#elf_l1_Lw