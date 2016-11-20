import React from 'react';
import { render } from 'react-dom';

export default class MailApp extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            "message": false,
            "messages": null,
            "isLoading": true,
            "reply": false,
            "replyMessage": null
        };

        this.closeMessage = this.closeMessage.bind(this);
        this.showReplyMessageBox = this.showReplyMessageBox.bind(this);
        this.sendReply = this.sendReply.bind(this);
        this.getMessages = this.getMessages.bind(this);
        this.viewMessage = this.viewMessage.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }

    static getUnreadStyle(isRead) {
        if (!isRead) {
            return {
                "backgroundColor": "#aa4444"
            }
        }
    }

    componentDidMount() {
        this.getMessages(true);
    }

    getMessages(loop) {
        let self = this;

        axios.get("/api/inbox").then(function (response) {
            self.setState({
                "messages": response.data,
                "isLoading": false
            });
        }).catch(function (error) {
            // @todo: implement
        });

        if (loop) {
            setTimeout(function() {
                this.getMessages(true);
            }.bind(this), 60000);
        }
    }

    viewMessage(id) {
        let self = this;

        return function () {
            this.setState({"isLoading": true});

            axios.get("/api/inbox/" + id).then(function (response) {
                self.setState({
                    "message": response.data[0],
                    "isLoading": false
                });
            }).catch(function (error) {
                // @todo: implement
            });
        }.bind(self);
    }

    closeMessage() {
        this.setState({
            "message": false,
            "isLoading": true,
            "messages": false,
            "reply": false,
            "replyMessage": null
        });

        this.getMessages();
    }

    showReplyMessageBox() {
        this.setState({"replyMessage": null, "reply": true});
    }

    sendReply() {
        let self = this;

        this.setState({"isLoading": true});

        axios.post("/api/inbox/reply/" + this.state.message.id, {"message": this.state.replyMessage}).then(function (response) {
            self.setState({"isLoading": false});
            self.closeMessage();
        }).catch(function (error) {
            // @todo: implement
        });
    }

    handleChange(event) {
        this.setState({"replyMessage": event.target.value});
    }

    renderPrivateMessage() {
        return <div>
            <div className="form-card mdl-card mdl-shadow--2dp">
                <div className="mdl-card__supporting-text">
                    <div>
                        <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <b>Timestamp</b> {this.state.message.timestamp}
                        </div>
                    </div>

                    <div>
                        <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <b>Sender</b> {this.state.message.from.firstName} {this.state.message.from.lastName}
                        </div>
                    </div>

                    <div>
                        <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <b>Subject</b> {this.state.message.subject}
                        </div>
                    </div>

                    <div>
                        <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <b>Message</b><br />
                            <pre>
                                {this.state.message.message}
                            </pre>
                        </div>
                    </div>

                    {this.state.reply && <div>
                        <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <label htmlFor="replyMessage"><b>Reply</b></label>
                            <textarea
                                id="replyMessage"
                                rows="20"
                                cols="60"
                                name="reply"
                                className="mdl-textfield__input"
                                value={this.state.replyMessage}
                                onChange={this.handleChange} />
                        </div>
                    </div>}
                </div>

                <div className="mdl-card__actions mdl-card--border">
                    {!this.state.reply &&
                    <button className="mdl-button mdl-js-button mdl-js-ripple-effect" onClick={this.showReplyMessageBox}>
                        Reply <i className="material-icons">reply</i>
                    </button>}

                    {this.state.reply &&
                    <button className="mdl-button mdl-js-button mdl-js-ripple-effect" onClick={this.sendReply}>
                        Send reply <i className="material-icons">send</i>
                    </button>}

                    <button className="mdl-button mdl-js-button mdl-js-ripple-effect btn-submit-form" onClick={this.closeMessage}>
                        Close <i className="material-icons">clear</i>
                    </button>
                </div>
            </div>
        </div>
    }

    renderPrivateMessages() {
        let self = this;

        return <table className="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
            <thead>
                <tr>
                    <th className="mdl-data-table__cell--non-numeric">timestamp</th>
                    <th className="mdl-data-table__cell--non-numeric">subject</th>
                    <th className="mdl-data-table__cell--non-numeric">sender</th>
                </tr>
            </thead>

            <tbody>
            {this.state.messages.map(function (listValue) {
                return <tr key={listValue.id} style={MailApp.getUnreadStyle(listValue.is_read)} onClick={self.viewMessage(listValue.id)} className="pointertr">
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.timestamp }</td>
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.subject }</td>
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.from }</td>
                </tr>
            })}
            </tbody>
        </table>
    }

    render() {
        if (this.state.isLoading) {
            return <div>
                Loading..
            </div>
        } else {
            if (this.state.message) {
                return this.renderPrivateMessage();
            } else {
                return this.renderPrivateMessages();
            }
        }
    }
}

render(
    <MailApp/>,
    document.getElementById('mailApp')
);
