import React from 'react';
import { Divider, Button, Modal } from 'semantic-ui-react'
import TextAreaField from '../components/textArea.jsx';
import NavigationButtons from '../helpers/navigation-buttons.jsx';
import BaseApp from "../components/base-app.jsx";
import request from 'superagent';

export default class ModalReplyPrivateMessage extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            modalOpen: false,
            modalLoading: true,
            message: "",
            app: 'inbox'
        };

        this.openModal = this.openModal.bind(this);
        this.closeModal = this.closeModal.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleReplySubmit = this.handleReplySubmit.bind(this);
    }

    openModal() {
        this.setState({"openModal": true});
    }

    closeModal() {
        this.setState({"openModal": false});
    }

    handleChange(event) {
        this.setState({"message": event.target.value});
    }

    handleReplySubmit(event) {
        let self = this;

        request
            .post(BaseApp.getApplicationDataUrl(this.state.app) + '/reply')
            .send({
                'message': this.state.message,
                'replyToId': this.props.replyToId
            })
            .end(function (err, res) {
                if (!err) {
                    self.closeModal();
                }
            });
    }

    render() {
        return <Modal trigger={<Button onClick={this.openModal} secondary>Reply</Button>}>
            <Divider horizontal>Reply</Divider>

            <Modal.Content>
                <div className="ui form">
                    <div className="field">
                        <TextAreaField width="twelve" name="message" label="Message" value={this.state.message} handleChange={this.handleChange} />
                    </div>

                    <NavigationButtons
                        footer={true}
                        handleSubmit={this.handleReplySubmit}/>
                </div>
            </Modal.Content>
        </Modal>
    }
}
