import React from 'react';

export default class Sort extends React.Component {
    static sortBy(field, reverse, primer) {
        let key = primer ?
            function(x) {return primer(x[field])} :
            function(x) {return x[field]};

        reverse = !reverse ? 1 : -1;

        return function (a, b) {
            return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
        }
    }
}
