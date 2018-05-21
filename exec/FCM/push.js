const FCM = require('fcm-node');

const serverKey = 'AAAAMEnbPZQ:APA91bH0dcr1PS5-RBMDQLWhq-2pj0znhfF1VIk5Ya789Ll6QPF4m2StdFdFdy5L3GCqCaxyd0EKIYRVELR2GJkofjUx7PQC65MDzhLuNisAja-ANm-iUrAVRLFBmBHyjpNnOwZMQDZ7';

const fcm = new FCM(serverKey);
const devices = [
    'c_PfoKvw0xM:APA91bH48R0MTbtKUd_6ObDOnh8C70tiANYvpSsdWvG11hIAQGz-iX1c0E2GcYirpsOYwRsbTpYJJ2H9EYgR9dl70uJmmXoL9Km_srvWdpQbKNkp3CFhoQxkH-ggrrqUhEhIQ66_RIIZ',
    'fm6oxv3Dg-g:APA91bFA8I5pdwXw4wD7pjZm6OJOQuIDDrdllyApvriDPhKP9OTm8S6SMC-ptwtrB-WvKWePUSorkUUYr5UKptd-cBeMVPMRdvSPLz-iZRhOCwSIJt-2-ANpD001cTTCijwzxiZLkUWc',        
    'fhIGLhQHG68:APA91bGmQc60csKDbKDxQ5xcBS1DS4Cqs02PHsWsPph6U1ss70F-9Fk7JYXwbTCxsZhDIolIblB2lY_X4G9CdHazuvGVJL3k-Zg8iEGaCyC-iKSS3XhrIadwroS87SrtL6m-yszVx8td'
];

devices.forEach((to) => {
    let message = {
	collapse_key: 'wash',
	notification: {
	    tag: 'wash',
	    title: 'Pračka doprala!',
	    body: 'Má paní, tvé prádlo je čisté.',
	    sound: 'default',
	}
    };

    message.to = to;
    fcm.send(message, (err, response) => {
	if(err) {
	    console.log("Couldnt send message", err);
	}
    });
});




