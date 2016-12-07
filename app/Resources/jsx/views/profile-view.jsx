import React from 'react';
import { render } from 'react-dom';
import TextField from '../components/text.jsx';
import { Divider, Button } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';

export default class ProfileView extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            displayQrCode: false
        };

        this.componentWillReceiveProps = this.componentWillReceiveProps.bind(this);
        this.toggleQrCode = this.toggleQrCode.bind(this);
    }

    componentWillReceiveProps(props) {
        if (!props.loading && !props.row) {
            this.props.setRow(props.data.shift());
        }
    }

    toggleQrCode() {
        this.setState({"displayQrCode": !this.state.displayQrCode});
    }

    render() {
        if (this.props.loading) {
            return <div></div>
        }

        if (this.props.row) {
            return <div>
                <Divider horizontal>Unit #id</Divider>

                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <h4 className="ui dividing header">General Information</h4>

                    <div className="two fields">
                        <TextField readonly={true} name="username" label="Username" value={this.props.row.username} />
                        <TextField readonly={true} name="email" label="Email" value={this.props.row.email} />
                    </div>

                    <div className="two fields">
                        <TextField name="firstName" label="First name" value={this.props.row.firstName} handleChange={this.props.handleChange} />
                        <TextField name="lastName" label="Last name" value={this.props.row.lastName} handleChange={this.props.handleChange} />
                    </div>

                    <div className="field">
                        <TextField readonly={true} name="tfaSecret" label="2FA Secret" value={this.props.row.tfaSecret} />
                        <Button onClick={this.toggleQrCode} basic color="red">Show/hide TFA QR code</Button>
                    </div>

                    <img style={{display: this.state.displayQrCode ? 'block' : 'none'}} src={this.props.row.tfa.qrCode}/>

                    <NavigationButtons
                        footer={true}
                        handleSubmit={this.props.handleSubmit}/>
                </div>
            </div>
        }

        return <div></div>
    }
}
