# Progetto test 

## Struttura codice

Ho cercato di dividere il codice in vari file in modo da renderlo meno ripetitivo e più leggibile.
- La cartella controller comprende il codice dell’applicazione.
- La cartella view comprende l’html dell’applicazione.

## Descrizione applicazione 


- La card per aggiungere gli abbonati, ho deciso di mostrarla solo nella modifica del prodotto, nella creazione del prodotto non serve perché fino a quando il prodotto non viene salvato nel db non si possono aggiungere abbonati per quel prodotto.

- Quando viene modificato un prodotto con quantità 0, a una quantità superiore a 0, verrà mostrato un messaggio che informa l’invio di un email a tutti gli abbonati di quel prodotto, infine gli abbonati a quel prodotto verranno eliminati.

- Ogni volta che si aggiunge modifica o elimina un prodotto, verra mostrato un messaggio di conferma.

- Ogni volta che si aggiunge o elimina un abbonato, verra mostrato un messaggio di conferma.

- Quando verrà aggiunto un abbonato a un prodotto, se viene salvata una mail che è già presente per quel prodotto, allora verrà mostrato un messaggio per informare di inserire una mail diversa.

- Quando viene aggiunto un prodotto, se si inserisce un codice prodotto già presente nel db, allora verra mostrato un messaggio che informa di inserire un codice prodotto diverso.

- Ho aggiunto bootstrap per dare un po di stile al progetto.
