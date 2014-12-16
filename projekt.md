#Projekt - BeerStash
Webbteknik II(1dv449) - Linnéuniversitetet

Andreas Fridlund - afrxx09

###Beskrivning
En applikation där användaren kan lägga till öl i sin “stash”, med andra ord vad man har hemma. Användaren ska kunna leta upp öl från befintliga källor(ratebeer.com) för att sedan bara ange antalet de har. Huvud-sidan för användaren ska lista alla öl man har hemma och det ska finnas en knapp man trycker på så reduceras antalet med en. Då kan man ha en trevlig interaktiv öl-meny på exempelvis en surfplatta när man har besök så gästerna ser vad som finns.
Användare ska kunna “bli vän” med andra användare och på så sätt dela sitt öl-utbud med varandra så man kan titta på vad kompisarna har hemma.
Man ska även kunna hitta folk baserat på vilka öl de har. På så sätt kan öl-fantaster med mer exklusiva öl hitta varandra för snack eller byteshandel.
Det kommer inte finnas någon kommunikation i applikationen, men man ska kunna exportera sina kontakter till sina kontakter i gmail samt leta upp dem med hjälp av nummerupplysning som 188100 eller teleadress.se

###Teknisk specifikation
Applikationen ska vara gjort i node js på serversidan med en mongodb-databas.
APIer som ska användas är i första hand ratebeer.com för att leta upp öl och 118100 för att hitta personer. Ratebeer sparar alla sina öl i en zip-fil som uppdateras varje vecka så jag tänker synka den mot min databas och använda den som referens mot deras api för att minska beslastningen mot dem.
