/*
paypal.Buttons({

    // Set up the transaction
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    total: "0.54",
                    currency: "ILS"
                },
                items: [{
                    name: "NeoPhone",
                    price: "0.54",
                    currency: "ILS",
                    quantity: "1"
                }]
            }]
        });
    },

    // Finalize the transaction
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // Show a success message to the buyer
            alert('Transaction completed by ' + details.payer.name.given_name + '!');
        });
    }


}).render('.paypal-wrapper');*/