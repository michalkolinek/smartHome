const FCM = require('fcm-node');

const serverKey = 'AAAAMEnbPZQ:APA91bH0dcr1PS5-RBMDQLWhq-2pj0znhfF1VIk5Ya789Ll6QPF4m2StdFdFdy5L3GCqCaxyd0EKIYRVELR2GJkofjUx7PQC65MDzhLuNisAja-ANm-iUrAVRLFBmBHyjpNnOwZMQDZ7';

const fcm = new FCM(serverKey);
const devices = [
    'cJ6NTo788bM:APA91bEFfPvoBjlZpSqm6SBCogtCTcn8mKDkYVJCfVPfRkV5NhH_XSna3pEAJHsLK4hM6Cknjb8LzevsSRl2SzWlVjUUyTn2to7JxIl1U28JQNVcb7n-rOQKhOXUgrpCrCyMeSe9YFnK',
    'dOX3S5-diWc:APA91bHz1k4-HayH1C-IVnIkCSHZzT2a4fmXFMeYhlmtkSLtSeGPujzum52Bh7X2PpGybNxpz5J6GysBctae5kU3Z5Ww__2UvofRcw4hrDnCE-NuC5t84Xw8r65mseCHQwxmP9-dMMlX'
];

devices.forEach((to) => {
    let message = {
	collapse_key: 'wash',
	notification: {
	    tag: 'wash',
	    title: 'Pračka doprala!',
	    body: 'Má paní, Tvé prádlo je čisté.',
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




