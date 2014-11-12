#Reflektion Lab 1

##Vad tror Du vi har för skäl till att spara det skrapade datat i JSON-format?

JSON är populärt lagringsformat för webbapplikationer och det är inte bundet till endast ett programmeringsspråk.
De sparade filerna kan lätt användas i en annan applikation, de kan föras över till ett annat system, tolkas och lagras om i exempelvis en databas utan större problem.
JSON är endast text så det krävs inga speciella program eller licenser för att kunna läsa filerna.

##Olika jämförelsesiter är flitiga användare av webbskrapor. Kan du komma på fler typer av tillämplingar där webbskrapor förekommer?

Forskning är väl det som jag kommer på först, och där finns det oändliga möjligheter. Antingen om man skrapar "gammal" data, data som publicerats över en lång tid eller
om man börjar skrapa data efter hand som den skapas under en längre tid så kan man göra research på nästan vad som helst.

Det finns ju också ett värde i att organisera data. Vanlig öppen "publik" data kanske inte betyder så mycket när det inte finns någon kontext. Skrapar man datan, länka ihop den och ger den relevans i
relation till annan data så kan man ha gjort den användbar eller rent av värdefull. Om man exempelvis skulle skrapa twitter för att ta reda på realtioner mellan olika användare, hur ofta de taggar varandra,
vilka som känner vilka osv.

Man skulle kunna skrapa sina kreditkorts-historik för att kunna kategorisera och kontrollera vart ens pengar tagit vägen. Lite mer personlig data så det kanske man inte vill dela med sig av.

##Hur har du i din skrapning underlättat för serverägaren?

Jag har identifierat mina anrop med hjälp av user-agent där jag anger mitt namn och min studentmail så de kan filtrera ut mina anrop från statistik. Om jag skulle ha orsakat problem så kan de enkelt kontakta mig.

Under utveckling och tesning har jag sällan kört full skrapning, utan istället begränsa applikationen att bara skrapa några sidor för att inte belasta servern för mycket.

##Vilka etiska aspekter bör man fundera kring vid webbskrapning?

För det första: Får jag skrapa det jag vill från mitt tänkta mål?

Vad ska datan användas till? Om det är skraping av publik men endå personlig data av och om människor så kanske man ska tänka igenom hur och om det ska publiceras.

##Vad finns det för risker med applikationer som innefattar automatisk skrapning av webbsidor? Nämn minst ett par stycken!

Då HTML är så löst och förlåtande språk så kan sidors struktur se ut lite hur som helst och dessutom ofta inte validera. Om de inte har korrekt HTML så kan det lätt bli fel när man ska tolka dokumenten.

Ägarna till källan man skrapar kan uppdatera / ändra sin struktur på dokumenten vilket gör att skrapan slutar fungera eller skrapar "fel"

Det finns risk att man hamnar i länk-loopar och mer eller mindre ddos:ar källan man skrapar.

##Tänk dig att du skulle skrapa en sida gjord i ASP.NET WebForms. Vad för extra problem skulle man kunna få då?

Det jag kommer på och som vi tog upp på lektionen är "viewstate". Den automatiskt genererade formulärdatan som är hashad och oläslig som används av applikationen på olika sätt.

Mycket av syftet med webforms är ju just formulär, så det kan vara krångligt att skrapa då man kanske måste analysera många formulär och simulera POST-requests för att komma åt datan.

##Välj ut två punkter kring din kod du tycker är värd att diskutera vid redovisningen. Det kan röra val du gjort, tekniska lösningar eller lösningar du inte är riktigt nöjd med.

Jag valde att separera skrapningen av länkarna till de olika sidorna och skrapningen av sidornas innehåll. Då enligt min vetskap, och som även en utomstående observation skulle påvisa, så uppdateras sällan antalet kurs-sidor.
Då är det lite overkill att gå igenom dem varje gång. Detta ger mig också möjligheten att spara ner och kategorisera länkarna till det olika sidorna.

Jag har även valt att inte lägga in någon form av automatisk cachning. Då min applikation har ett UI så sker ingen skrapning automatiskt, den måste manuellt aktiveras av användaren.
Denna har tillgång till statistik som visar när de olika delarna skrapades, man behöver dessutom inte skrapa allt på en gång utan kan välja specifika kategorier att skrapa.

##Hitta ett rättsfall som handlar om webbskrapning. Redogör kort för detta.

###eBay vs Bidders Edge
Lite extra intressant då detta hände för "länge sedan". Bidders Edge skapades 1997 och fick 1998 lov av eBay att fortsätta skrapa annonser av vissa typer. 1999 gav eBay lov att använda automatiska crawlers i 90 dagar.
Någon stans på vägen här efter började det knaka i fgorgarna för de kom inte överens om hur det skulle ske rent tekniskt. eBay försökte till slut stoppa Bidders Edge genom IP-blockning och andra metoder men det hjälpte inte.
Mer komplikationer ledde i sin tur till att det blev en rättslig prövning i slutet på 1999. Efter över ett år av rättegång så fick eBay rätt i frågan och Bidders edge lade i samband med detta ner sin verksamhet.

Källa. wikipedia såklart:
[Länk](http://en.wikipedia.org/wiki/EBay_v._Bidder%27s_Edge)

##Känner du att du lärt dig något av denna uppgift?

Jag har självklart kännt till skrapning och spindlar innan, men aldrig gått in i detalj på hur de faktiskt fungerar. Så det har varit lärorikt att få prova på just hur man gör en enkel skrapa.

Det har också varit intressant att diskutera de olika aspekterna kring ämnet, etik, moral och syften. Framförallt i samband med semanstiska webben där jag nu fått mer insikt i vikten(värdet) av att skrapa data,
tolka den. Har redan börjat fundera kring vilken typ av data man skulle kunna skrapa och "semantisera" för att prova göra någon form av linked-data applikation.

