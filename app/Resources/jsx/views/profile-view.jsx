import React from 'react';
import TextField from '../components/text.jsx';
import { Button, Header, Modal, Icon, Message, Image, Input, Segment, Menu } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';
import request from 'superagent';
const _ = require('lodash');

export default class ProfileView extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            displayQrCode: false,
            tfaModalOpen: false,
            profilePictureModalOpen : false,
            uploadedPicture : null,
            disableTfaModalOpen: false,
            tfaConfirmed: false,
            tfaConfirmation: '',
            checkingQrCode: false,
            activeItem: 'details'
        };

        this.componentWillReceiveProps = this.componentWillReceiveProps.bind(this);
        this.openProfilePictureModal = this.openProfilePictureModal.bind(this);
        this.closeProfilePictureModal = this.closeProfilePictureModal.bind(this);
        this.openTfaModal = this.openTfaModal.bind(this);
        this.closeTfaModal = this.closeTfaModal.bind(this);
        this.openDisableTfaModal = this.openDisableTfaModal.bind(this);
        this.closeDisableTfaModal = this.closeDisableTfaModal.bind(this);
        this.enableTfa = this.enableTfa.bind(this);
        this.disableTfa = this.disableTfa.bind(this);
        this.checkTfa = this.checkTfa.bind(this);
        this.handleItemClick = this.handleItemClick.bind(this);
        this.fileChanged = this.fileChanged.bind(this);
        this.uploadProfilePicture = this.uploadProfilePicture.bind(this);
        this._createPreview = this._createPreview.bind(this);
        this.getProfilePicture = this.getProfilePicture.bind(this);
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

    closeProfilePictureModal() {
        this.setState({"profilePictureModalOpen": false});
    }

    openProfilePictureModal() {
        this.setState({"profilePictureModalOpen": true});
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

        if (this.state.tfaConfirmation.length === 6) {
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

    handleItemClick(e, obj) {
        this.setState({"activeItem": obj.name});
    }

    uploadProfilePicture() {
        let self = this;

        request
            .post('/api/user/picture', {image: this.state.imageUrl})
            .end(function (err, res) {
                self.setState({'uploadedPicture': self.state.imageUrl});
                self.closeProfilePictureModal();
            });
    }

    fileChanged(e) {
        let files;
        if (e.dataTransfer) {
            files = e.dataTransfer.files;
        } else if (e.target) {
            files = e.target.files;
        }

        _.each(files, this._createPreview);
    }

    _createPreview(file){
        /**
         * Work in progress.
         */
        let self = this, reader = new FileReader();

        reader.onloadend = function(e){
            self.setState({'image': file, 'imageUrl': e.target.result});
        };

        reader.readAsDataURL(file);
    }

    getProfilePicture() {
        if (this.state.uploadedPicture) {
            return this.state.uploadedPicture;
        }

        if (this.props.row.profilePicture && this.props.row.profilePicture.url) {
            return this.props.row.profilePicture.url;
        }

        return 'data:image/png;base64,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';
    }

    render() {
        if (this.props.loading) {
            return <div></div>
        }

        if (this.props.row) {
            return <div className="ui segment">
                <Menu attached='top' tabular>
                    <Menu.Item name='details' active={this.state.activeItem === 'details'} onClick={this.handleItemClick}/>
                    <Menu.Item name='security' active={this.state.activeItem === 'security'} onClick={this.handleItemClick}/>
                </Menu>

                <Segment attached='bottom'>
                    <div className={"ui form " + (this.props.loading ? "loading" : "")}>

                    {this.state.activeItem === 'details' && <div>
                        <Modal open={this.state.profilePictureModalOpen} trigger={<Image onClick={this.openProfilePictureModal} className="profilePicture" src={this.getProfilePicture()} size='small' shape='circular' floated='left' />}>
                            <Modal.Header>Select a Photo</Modal.Header>
                            <Modal.Content image>
                                <Modal.Description>
                                    <Header>Upload new profile picture</Header>
                                    <input type="file" name="pic" ref='fileInput' accept="image/*" onChange={this.fileChanged}/>
                                </Modal.Description>
                            </Modal.Content>

                            <Modal.Actions>
                                <Button color='red' inverted onClick={this.closeProfilePictureModal}>
                                    <Icon name='remove' /> Cancel
                                </Button>
                                <Button color='green' inverted>
                                    <Icon name='checkmark' onClick={this.uploadProfilePicture} /> Save
                                </Button>
                            </Modal.Actions>
                        </Modal>

                        <div className="two fields">
                            <TextField readonly={true} name="username" label="Username" value={this.props.row.username} />
                            <TextField readonly={true} name="email" label="Email" value={this.props.row.email} />
                        </div>

                        <div className="two fields">
                            <TextField name="firstName" label="First name" value={this.props.row.firstName} handleChange={this.props.handleChange} />
                            <TextField name="lastName" label="Last name" value={this.props.row.lastName} handleChange={this.props.handleChange} />
                        </div>
                    </div>}

                    {(this.state.activeItem === 'security' && !this.props.row.tfaEnabled) && <div className="field">
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
                                            error={this.state.tfaConfirmation.length === 6 && !this.state.checkingQrCode && !this.state.tfaConfirmed}
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

                    {(this.state.activeItem === 'security' && this.props.row.tfaEnabled) && <div className="field">
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

                    </div>
                </Segment>

                <Menu attached='bottom' tabular>
                    <NavigationButtons
                        footer={true}
                        handleSubmit={this.props.handleSubmit}/>
                </Menu>
            </div>
        }

        return <div></div>
    }
}
