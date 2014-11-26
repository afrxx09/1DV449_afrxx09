#Reflektion Lab 2

##Moment 1 - Säkerhet

###Authentication
Existerar ej, oavsett om användaren finns i databasen eller inte så släpps man in.
Så "IsUser"-funktionen antingen returnerar ett userId eller en sträng så kommer if-satsen i "check.php" bli "true". Eller rättare sagt den kommer aldrig bli false.
Lösning: Gjort om funktionen så den faktiskt kontrollerar att användaren finns i databasen och lösenorder stämmer.

Logout-knappen gör inget mer än att skicka en till startsidan. Alla sessioner är kvar.
Vem som helst som använder datorn efter att någon försökt "logga ut" kommer in igen.

__Lösning_: Har rättat till logout, lagt det i en egen fil och länkar till den när man klickar på logga ut. 


###Authorization
"mess.php" kontrollerar aldrig om man är behörig att se sidan och använder inte sessionsvariablerna alls.
Vem som helst kommer åt applikationen och kan skapa meddelanden.

__Lösning__: Lagt till en login-check på mess.php

Även de php-filer som anropas via ajax saknar kontroller för autensiering och behörigheter. Till skillnad från mess.php så startas sessionerna här och det finns tillgång till lite säkerhets-funktioner.
Om en elak användare kommer åt javascripten från applikationen kan den lätt hitta vart alla ajaxanrop skickas och skicka egna anrop dit utan att de kontrolleras om anropen kommer från en inloggad användare.

__Lösning_: Gör logincheck på varje ajaxanrop

###SQL-injects

"post.php" som lägger till meddelanden i databasen använder varken prepared statements eller tvättar för indatan som postas.
En användare kan skicka in SQL-kod för att komma åt eller skada databsen.

__Lösning__: Har lagt till prepared statements i alla SQL-erna.

###XSS

Det är fritt fram för xss-attacker då ingen data tvättas alls varken när den tas emot eller presenteras.
Det går att skicka in script-taggar med javascript, men även img-taggar och länkar till skadlig data.
En elak anvädare kan skicka in javascript-kod som gör att script körs när datan(scripten) presenteras och där med exekveras. Kan exempelvis stjäla sessionen.

__Lösning__: All indata tvättas med "htmlentities".

###CSRF

Applikationen ä helt öppen för csrf-attacker och kan lägga till nya meddelanden om ett sådan script så skulle önska.
Om någon känner till vår applikation så kan de programmera ett script som försöker skicka massa requests om det skulle vara så att användaren är inloggad.

__Lösning__: Skapar en token som renderas ut i en meta-tag i headern. Denna skickas med i ajax-anropen och kontrolleras på serversidan.

###Databasen

Lösenorden är inte hashade och sparade i klartext vilket man märker på hur "IsUser"-funktionen ser ut.

Finns lösenorden i klartext så kan obehöriga se lösenordet om de kommer åt databasen.

__Lösning__: räcker att nämna? Lösenord ska hashas och innan de lagras så det inte går att få fram lösenordet igen. Glöms lösenordet bort så får man nollställa det eller generera ett nytt.

###GET vs POST

Alla Ajax-anropen görs med GET.

I vanliga fall är GET sämre för det sparas i historiken, parametrar står i klartext i url:en("Sensitive Data Exposure").

__Lösning__: Ändrat till POST.

##Moment 2 - Optimering

###Observationer

* CSS finns blandat i HTML-headern och i taggar. Skulle vilja lägga allt i en och samma fil och inkludera den.
* Finns två javascript-filer till bootstrap, båda inkluderas.
* Finns två jQuery-filer, det är den icke minifierade som används dessutom.
* Message-"klassen" kan läggas in i MessageBoard.js så blir det en mindre request att ladda.
* MessageBoard binder funktionen showTime till varenda meddelande. Detta äter snabbt up minne på klienten när antalet meddelanden växer. Borde binda på "MessageContainer" och låtat eventet bubbla dit. Då behövs bara ett event oavsett hur många meddelanden som finns.
* RemoveMessage-funktionen används inte och tar dessutom bara bort ett meddelande visuellt, inte ur databasen. Ta bort eller implementera.
* RenderMessages används inte heller.
* Span-taggen "nrOfMessages" borde sparas som variabel i messageboard istället för att hämtas med selector återupprepade gånger. Samt att det vore snyggare med en funktion vars syfte är att uppdatera räknaren.
* Onblur och onfocus binds till textarean för att toggla css.classer som inte behövs, använd css-psuedo klasserna istället.
* Event-hanteringen bör kollas över och köra med attachEvent||addEventListener istället för ".onclick()"
* get.php inkluderas utan att användas i mess.php
* functions.php hade kunnat inkludera endast den filen som behövs för just det funktionsanropet.
* Tar en ganska onödig vända via test/debug.php när man lägger till meddelanden.
* php_errors.log verkar inte användas alls.
* sec.php är mer elle mindre värdelös då den enda funktionen som används inte ger önskat resultat endå.

###Åtgärder

####Utgångspunkt för tester
Testar i Firefox med firebug och refreshar med ctrl+f5 för att inte använda cache.

* Antal requests: 14
* Responstid: 500ms-550ms
* Storlek: 981kb

####Externa styles
Flyttade all CSS till "dyn.css" som för övrigt inte inkluderades alls. Håller appens css och bootstrap separerat, orimligt att merga dessa.

* Antal requests: 15
* Responstid: oförändrad
* Storlek: 981kb

Även om det faktiskt är snabbare att ha inline css och javascript pga overhead med fler http-requests så är det bättre att dela upp dem i egna filer. Resursfiler som css och javascript kan cachas och behöver därmed inte laddas ner om och om igen. Detta håller antalet requests nere och storleken på HTML-filen också.

####Dubbel-script
Tog bort dubbla inkluderingen av script

* Antal requests: 13
* Responstid: 400-450ms
* Storlek: 669kb

Både jQuery och bootstrap laddas in två gånger, detta get 2 extra requests som absolut inte behövs. Utöver detta så måste de också exekveras och tolkas. 

####Flytta alla script till botten på body

* Antal Requests: 13
* Responstid: 450ms+
* Storlek: 669kb

Resulterade av någon anledning i aningen längre responstid, mellan 1-50 ms ökning på de flesta försöken. Detta är konstigt då script blockar den parallella nerladdningen av resurser men tillåter multipla script att laddas samtidigt. Då sidan tidigare laddade in script på flera olika ställen borde detta gjort att det tog längre tid. Nu laddas alla script in sist och tillsammans så det borde gåt snabbare.

####Minska antalet requests
Bakgrunden i body-taggen och backgunden i CSS-filen som "inte används" kan tas bort.

Requesten efter longpoll.js som inte finns gör 2 requests till 404's i onödan.

tog bort favicon och apple-touch icons då ingen av dem var korrekt implementerade eller fungerande. Gjorde visserligen inga http-requests, men tog bort dem endå.

* Antal Requests: 10
* Responstid: 375-400ms
* Storlek: 526kb

####Minifierade filer
Minifierade bootstrap.js och använde minifierade jQuery istället för den icke minifierade.

* Antal Requests: 10 
* Responstid: 325-350ms
* Storlek: 308kb


####CDN

Använda CDN för att minska data-trafiken och antalet requests från min egen server.
Detta fungerar dock inte i detta testet då allt körs lokalt och ett par externa requestst ökar responstiden avsevärt. 

Hämtar bootstrap css, bootstrap js och jquery från andra källor.

* Antal Requests: 10(7 lokala 3 externa CDN)
* Responstid: 700ms
* Storlek: 123kb

###Resultat ( utan CDN )

* Antalet requests sänktes från 14 till 10 (~71,5%)
* Responstiden sänktes från ~550ms till ~350ms (~63%)
* Storleken 981kb till 308kb (~31%)

###Mer optimeringar.

Cache, gzip och keep-alive och sådana inställningar är ofta påslagna per automatik, min lokala server hade även det.

##Moment 3 - Longpoll

Istället för att funktionen getMessages hämtar alla meddelanden när applikationen startas så initieras en "rekursiv" timout-funktion som kallar på sig själv så fort den är klar. Den funktionen skickar ett ajax anrop till servern och frågar efter nya meddelanden. Den håller reda på vad som är nytt genom att skicka med id på det senaste meddelandet som tagit emot. När inga meddelanden finns(när appen startas) så är det id't 0, vilket betyder att alla meddelanden med id högre än 0 hämtas.

På serversidan, så tar programmet emot förfrågan och loopar 30 varv och frågar databasen om det finns några nya poster, om inte så väntar det en sekund och kollar sedan igen. Om det kommer in en ny post under den tiden så avbryts loopen och programmet skickar tilbaka eventuella nya meddelanden. Om det inte kommer några nya posts under loopens livslängd så skickas det tomma svaret tillbaka och scriptet skickar med timeout-funktionen en ny förfrågan som repiterar hela processen.

Nackdelarna med denna taktiken är att scriptet ligger och "idlar" på servern hela tiden fast det oftast inte händer något, men värst av allt är väl att det skickas en sql-query varje skund vilket belastar databsen i "onödan".

Alternativet, att köra "polling" där man instället väntar exempelvis 30 sekunder på klienten mellan varje anrop är snällare mot servern, men det ger inte alls samma effekt hos användaren då de måste vänta så länge mellan varje uppdatering och får inte alls den där "live-känslan".

Största motgången var att komma på varför det inte gick lyssna efter meddelanden samtidigt som man skickade meddelanden. Dessa requests köades upp och det tog ett tag innan man listade ut att det var på grund av sessionen.

##Moment 4 - Websockets

Denna var klurig. Det finns små ramverk och biliotek för websockets i php, men det är svårt att hitta vettig information om hur dessa fungerar. Jag ville inte använda ett helt ramverk för att lösa uppgiften för det kändes som "fusk", ett exempel är "Rachet". Jag ville ha en så clean och minimalistisk lösning som möjligt för att förstå hur det fungerar. Först ville jag göra allt själv men insåg att det är ett ganska stort jobb att skriva en websocket-server.

Efter mycket läsning, tutorials som inte var kompletta och experimenterande så lyckades jag få igång en demo-appliaktion med [PHP-webbsocket](https://github.com/ghedipunk/PHP-Websockets) som faktiskt verkade fungera. Denna var dock inte så dynamisk då servern endast kunde ta emot rena strings och skicka tillbaka dem direkt till samma användare igen.

Efter lite trixande så kom jag på att jag kunde skicka json-objekt som strängar och köra decode på dem i php och på så sätt skicka mer komplexa objekt till servern. På så sätt kunde jag skicka olika events från javascripten med olika nyklar och tolka dem på server-sidan för att utföra olika saker. I detta fallet läsa meddelanden vilket man måste göra när applikationen startas och skicka meddelanden. Nya meddelanden som skickas efter att applikationen startats pushas ut automatiskt, som resultat av send-funktionen så dessa behövs aldrig hämtas eller frågas efter.

Vad jag inte har koll på i nuläget är säkerheten. När, var och hur ska jag kontrollera att användaren är inloggad, kontrollera rättigheter samt kontrollera in- och ut-datan. Går det xxs'a, csrf'a, stjäla sessioner med mera? troligtvis de två förstnämnda, sessioner vet jag inte alls hur det fungerar då det sker "handskakning" i uppkopplingen.

Till sist ska jag nämna att jag inte har möjlighet att lägga upp detta live, då det krävs tillgång till en terminal och att kunna köra individuella instanser av PHP med server-scriptet. Jag har helt enkelt inte rättigheter att starta mitt websocket-server-script på mitt nuvarande webhotell.