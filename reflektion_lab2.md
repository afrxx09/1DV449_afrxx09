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

##Moment 3 - Longpoll