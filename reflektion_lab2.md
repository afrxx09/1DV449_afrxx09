#Reflektion Lab 2

##Moment 1 - Säkerhet

###Authentication
Existerar ej, oavsett om användaren finns i databasen eller inte så släpps man in.
Så "IsUser"-funktionen antingen returnerar ett userId eller en sträng så kommer if-satsen i "check.php" bli "true". Eller rättare sagt den kommer aldrig bli false.

Logout-knappen gör inget mer än att skicka en till startsidan. Alla sessioner är kvar. 

###Authorization
"mess.php" kontrollerar aldrig om man är behörig att se sidan och använder inte sessionsvariablerna alls.

Även de php-filer som anropas via ajax saknar kontroller för autensiering och behörigheter. Till skillnad från mess.php så startas sessionerna här och det finns tillgång till lite säkerhets-funktioner.

###SQL-injects

"post.php" som lägger till meddelanden i databasen använder varken prepared statements eller tvättar för indatan som postas.

###XSS

Det är fritt fram för xss-attacker då ingen data tvättas alls varken när den tas emot eller presenteras.
Det går att skicka in script-taggar med javascript, men även img-taggar och länkar till skadlig data.

###CSRF

Applikationen ä helt öppen för csrf-attacker och kan lägga till nya meddelanden om ett sådan script så skulle önska.

###Databasen

Lösenorden är inte hashade och sparade i klartext vilket man märker på hur "IsUser"-funktionen ser ut.

###Sensitive Data exposure

Användarnamnet är sparat i en Sessions-variabel. Vet inte hur känsligt det är iofs.

Filnamn och get-parametrar är exponerade i javascripten.

###GET vs POST

Alla Ajax-anropen görs med GET istället för POST.


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