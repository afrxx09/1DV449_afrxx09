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

###CSS

CSS finns blandat i HTML-headern och i taggar. Skulle vilja lägga allt i en och samma fil och inkludera den.
Resultat: Troligtvis inget mätbart i ms, men kodstrukturen blir bättre(classic SoC).

###Bootstrap

Finns två javascript-filer till bootstrap, båda inkluderas. Ta bort "script.js" och ta bort inkluden så bör sidan laddas några ms snabbare samt att det är en request mindre som behöver göras.

Det går även att diskutera om bootstrap behövs alls i appen. Det enda det används till är några klasser för input-fälten och för knapparna. Detta hade enkelt lösts med 10-20 rader css. Då hade man sluppit bootstrap helt. Det är 2300 rader javascript och 7000 rader CSS som inte behöver läsas in och dessutom 2 mindre requests.

###jQuery

Används bara för ajax-anropen och det är enkla anrop, man skulle kunna göra native javascript ajax-anrop och slippa inkludera jQuery. Den är dock minified vilket är bra för laddningstiderna. Oavsett så är en mindre request bättre för svartiderna.

###Javascript

Message-"klassen" kan läggas in i MessageBoard.js så blir det en mindre request att ladda.

MessageBoard binder funktionen showTime till varenda meddelande. Detta äter snabbt up minne på klienten när antalet meddelanden växer.
Borde binda på "MessageContainer" och låtat eventet bubbla dit. Då behövs bara ett event oavsett hur många meddelanden som finns.

RemoveMessage-funktionen används inte och tar dessutom bara bort ett meddelande visuellt, inte ur databasen. Ta bort eller implementera.

RenderMessages används inte heller.
Span-taggen "nrOfMessages" borde sparas som variabel i messageboard istället för att hämtas med selector återupprepade gånger. Samt att det vore snyggare med en funktion vars syfte är att uppdatera räknaren.

Onblur och onfocus binds till textarean för att toggla css.classer som inte behövs, använd css-psuedo klasserna istället.

Event-hanteringen bör kollas över och köra med attachEvent||addEventListener istället för ".onclick()"

###PHP

get.php inkluderas utan att användas i mess.php

functions.php hade kunnat inkludera endast den filen som behövs för just det funktionsanropet.

Tar en ganska onödig vända via test/debug.php när man lägger till meddelanden.

php_errors.log verkar inte användas alls.

sec.php är mer elle mindre värdelös då den enda funktionen som används inte ger önskat resultat endå.

##Moment 3 - Longpoll