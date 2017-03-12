import React from 'react';
import BaseApp from './components/base-app.jsx';
import TaskView from './views/task-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import request from 'superagent';

export default class TaskApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.app = "task";

        let self = this;

        this.state.users = [];
        this.getData("/api/user", function (err, data) {
            data.forEach(function(user) {
                self.state.users.push({'value': user.id, 'text': user.name});
            });
        });

        this.state.customers = [];
        this.getData("/api/customer", function (err, data) {
            data.forEach(function(customer) {
                self.state.customers.push({'value': customer.id, 'text': customer.name});
            });
        });

        this.state.items = [];
        this.getData("/api/item", function (err, data) {
            data.forEach(function(item) {
                self.state.items.push({'value': item.id, 'text': item.name});
            });
        });

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        if (this.state.row.hasOwnProperty('id')) {
            request
                .put(BaseApp.getApplicationDataUrl(this.state.app))
                .send(this.state.row)
                .end(function (err, res) {
                    if (!err) {
                        NotificationManager.success("Row updated!", "Success");
                        return true;
                    }

                    self.setState({"errors": res.body.error_fields});
                    NotificationManager.error("An error occurred", "Problems detected");
                });
        } else {
            request
                .post(BaseApp.getApplicationDataUrl(this.state.app))
                .send(this.state.row)
                .end(function (err, res) {
                    if (!err) {
                        NotificationManager.success("Row updated!", "Success");
                        return true;
                    }

                    self.setState({"errors": res.body.error_fields});
                    NotificationManager.error("An error occurred", "Problems detected");
                });
        }
    }

    render() {
        return <div>
            <NotificationContainer/>

            <TaskView
                users={this.state.users}
                customers={this.state.customers}
                items={this.state.items}

                e={this.state.errors}
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
    }

    getEmptyModel() {
        return {}
    }
}
