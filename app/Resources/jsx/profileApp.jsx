import React from 'react';
import { render } from 'react-dom';
import BaseApp from './components/base-app.jsx';
import ProfileView from './views/profile-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import request from 'superagent';

export default class ProfileApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.app = "profile";

        this.handleSubmit = this.handleSubmit.bind(this);
        this.setRow = this.setRow.bind(this);
    }

    setRow(row) {
        this.setState({"row": row});
    }

    handleSubmit(event) {
        event.preventDefault();

        request
            .put(BaseApp.getApplicationDataUrl(this.state.app))
            .send(this.state.row)
            .end(function (err, res) {
                if (!err) {
                    NotificationManager.success("Row updated!", "Success");
                    return true;
                }

                self.setState({"errors": res.body.error_fields});
                NotificationManager.error("An error occured", "Problems detected");
            });
    }

    render() {
        return <div>
            <NotificationContainer/>

            <div className="ui segment">
                <div className={"ui inverted  " + (this.state.isLoading ? "active" : "") + " dimmer"}>
                    <div className="ui loader"></div>
                </div>

                <ProfileView
                    e={this.state.errors}
                    setRow={this.setRow}
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
    <ProfileApp/>,
    document.getElementById('profileApp')
);
