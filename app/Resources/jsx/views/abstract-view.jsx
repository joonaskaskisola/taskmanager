import React from 'react';

export default class AbstractView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    componentWillReceiveProps(props) {
        if (props.newAction) {
            if (!this.props.row) {
                props.createNew();
            }
        }
    }

    render() {
        return false;
    }
}
