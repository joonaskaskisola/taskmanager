import React from 'react';
import { render } from 'react-dom';
import TextField from '../components/text.jsx';
import { Divider, Button, Header, Modal, Icon, Message, Image, Input } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';
import request from 'superagent';

export default class ProfileView extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            displayQrCode: false,
            tfaModalOpen: false,
            disableTfaModalOpen: false,
            tfaConfirmed: false,
            tfaConfirmation: '',
            checkingQrCode: false
        };

        this.componentWillReceiveProps = this.componentWillReceiveProps.bind(this);
        this.openTfaModal = this.openTfaModal.bind(this);
        this.closeTfaModal = this.closeTfaModal.bind(this);
        this.openDisableTfaModal = this.openDisableTfaModal.bind(this);
        this.closeDisableTfaModal = this.closeDisableTfaModal.bind(this);
        this.enableTfa = this.enableTfa.bind(this);
        this.disableTfa = this.disableTfa.bind(this);
        this.checkTfa = this.checkTfa.bind(this);
    }

    componentWillReceiveProps(props) {
        if (!props.loading && !props.row) {
            this.props.setRow(props.data.shift());
        }
    }

    enableTfa() {
        let self = this;

        request
            .post("/api/tfa/enable")
            .end(function (err, res) {
                self.state.tfaModalOpen = false;
                self.props.row.tfaEnabled = true;

                self.setState(self.state);
            });
    }

    disableTfa() {
        let self = this;

        request
            .post("/api/tfa/disable")
            .end(function (err, res) {
                self.state.disableTfaModalOpen = false;
                self.props.row.tfaEnabled = false;

                self.setState(self.state);
            });
    }

    closeTfaModal() {
        this.setState({"tfaModalOpen": false});
    }

    openTfaModal() {
        this.setState({"tfaModalOpen": true});
    }

    closeDisableTfaModal() {
        this.setState({"disableTfaModalOpen": false});
    }

    openDisableTfaModal() {
        this.setState({"disableTfaModalOpen": true});
    }

    checkTfa(e) {
        this.state.tfaConfirmation = e.target.value;

        if (this.state.tfaConfirmation.length == 6) {
            this.state.checkingQrCode = true;

            let self = this;
            request
                .post("/api/tfa/verify", {tfa_key: this.state.tfaConfirmation})
                .end(function (err, res) {
                    self.setState({
                        "tfaConfirmed": res.body.isValid,
                        "checkingQrCode": false
                    });
                });
        } else {
            this.state.checkingQrCode = false;
        }

        this.setState(this.state);
    }

    render() {
        if (this.props.loading) {
            return <div></div>
        }

        if (this.props.row) {
            return <div className="ui segment">
                <Divider horizontal>Profile</Divider>

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

                    {!this.props.row.tfaEnabled && <div className="field">
                        <Modal open={this.state.tfaModalOpen} trigger={<Button onClick={this.openTfaModal}>Enable two factor authentication</Button>}>
                            <Modal.Header>Enable two factor authentication</Modal.Header>
                            <Modal.Content image>
                                <Image wrapped size='medium' src="/api/tfa/qrcode" />

                                <Modal.Description>
                                    {this.state.tfaConfirmed && <Message positive icon>
                                        <Icon name='checkmark' />
                                        <Message.Header>Everything seems to be in order!</Message.Header>
                                    </Message>}

                                    {!this.state.tfaConfirmed && <Message warning>
                                        <Message.Header>Save this key somewhere safe!</Message.Header>
                                        <p>Losing this key will result in account being locked!</p>
                                    </Message>}

                                    <div className="field">
                                        <Input
                                            disabled={this.state.tfaConfirmed}
                                            icon="code"
                                            loading={this.state.checkingQrCode}
                                            size="massive"
                                            value={this.state.tfaConfirmation}
                                            onChange={this.checkTfa}
                                            error={this.state.tfaConfirmation.length == 6 && !this.state.checkingQrCode && !this.state.tfaConfirmed}
                                            placeholder='123 456' />
                                    </div>

                                </Modal.Description>
                            </Modal.Content>
                            <Modal.Actions>
                                <Button color='red' inverted onClick={this.closeTfaModal}>
                                    <Icon name='remove' /> Cancel
                                </Button>
                                <Button color='green' inverted onClick={this.enableTfa} disabled={!this.state.tfaConfirmed}>
                                    <Icon name='checkmark' /> Saved, enable
                                </Button>
                            </Modal.Actions>
                        </Modal>
                    </div>}

                    {this.props.row.tfaEnabled && <div className="field">
                        <Modal open={this.state.disableTfaModalOpen} trigger={<Button color='red' onClick={this.openDisableTfaModal}>Disable two factor authentication</Button>} basic size='small'>
                            <Header icon='warning sign' content='Disable two factor authentication?' />
                            <Modal.Content>
                                <p>Are you sure you would like to disable two factor authentication?</p>
                            </Modal.Content>
                            <Modal.Actions>
                                <Button basic color='red' inverted onClick={this.closeDisableTfaModal}>
                                    <Icon name='remove' /> No
                                </Button>
                                <Button color='green' inverted onClick={this.disableTfa}>
                                    <Icon name='checkmark' /> Yes
                                </Button>
                            </Modal.Actions>
                        </Modal>
                    </div>}

                    <NavigationButtons
                        footer={true}
                        handleSubmit={this.props.handleSubmit}/>
                </div>
            </div>
        }

        return <div></div>
    }
}
