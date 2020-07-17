const increaseCounter = (jqueryObject, start, end) => {
    jqueryObject
        .prop('counter', start)
        .animate(
            {counter: end},
            {
                duration: 2000,
                easing: 'swing',
                step: (now) => jqueryObject.text(Math.ceil(now))
            }
        );
};

export default increaseCounter;
