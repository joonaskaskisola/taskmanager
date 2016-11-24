import React from 'react';
import {render} from 'react-dom';
import BaseApp from './components/base-app.jsx';
import InboxView from './views/inbox-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';

export default class InboxApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.dataUrl = "/api/inbox";
        this.state.loadExtraInfo = true;
        this.state.users = [];

        let self = this;

        this.getData("/api/user", function (err, data) {
            data.forEach(function(user) {
                self.state.users.push({'value': user.id, 'text': user.name});
            })
        });

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        if (this.state.row['id'] !== undefined) {
            axios.put(this.state.dataUrl, this.state.row).then(function (response) {
                NotificationManager.success("Row updated!", "Success");
            }).catch(function (error) {
                NotificationManager.error(error.toString(), "Problems detected");
            });
        } else {
            axios.post(this.state.dataUrl, this.state.row).then(function (response) {
                NotificationManager.success("Row updated!", "Success");
            }).catch(function (error) {
                NotificationManager.error(error.toString(), "Problems detected");
            });
        }
    }

    render() {
        return <div>
            <NotificationContainer/>

            <div className="ui segment">
                <div className={"ui inverted  " + (this.state.isLoading ? "active" : "") + " dimmer"}>
                    <div className="ui loader"></div>
                </div>

                <InboxView
                    users={this.state.users}
                    createNew={this.createNew}
                    showNext={this.state.next}
                    showPrev={this.state.prev}
                    nextRow={this.nextRow}
                    previousRow={this.previousRow}
                    handleSubmit={this.handleSubmit}
                    handleSelectChange={this.handleSelectChange}
                    handleChange={this.handleChange}
                    closeRow={this.closeRow}
                    viewRow={this.viewRow}
                    loading={this.state.isLoading}
                    row={this.state.row}
                    data={this.state.data}/>
            </div>
        </div>
    }
}

render(
    <InboxApp/>,
    document.getElementById('inboxApp')
);
