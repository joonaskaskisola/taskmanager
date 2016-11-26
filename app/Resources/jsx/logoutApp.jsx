import React from 'react'
import { render } from 'react-dom';
import { Confirm } from 'semantic-ui-react'

export default class LogoutApp extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            "open": false,
            "result": null
        };

        this.show = this.show.bind(this);
        this.handleConfirm = this.handleConfirm.bind(this);
        this.handleCancel = this.handleCancel.bind(this);
    }

    show() {
        this.setState({open: true});
    }

    handleConfirm() {
        window.location = '/logout';
        this.setState({result: 'confirmed', open: false});
    }

    handleCancel() {
        this.setState({result: 'cancelled', open: false});
    }

    render() {
        return (
            <div onClick={this.show}>
                <i className="sign out icon"></i>

                <Confirm
                    open={this.state.open}
                    onCancel={this.handleCancel}
                    onConfirm={this.handleConfirm}
                />
            </div>
        )
    }
}

render(
    <LogoutApp/>,
    document.getElementById('logoutApp')
);
