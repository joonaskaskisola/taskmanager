import React from 'react';
import {render} from 'react-dom';
import BaseApp from './components/base-app.jsx';
import CategoryView from './views/category-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import request from 'superagent';

export default class CategoryApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.app = "category";

        this.handleSubmit = this.handleSubmit.bind(this);
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

            <CategoryView
                e={this.state.errors}
                createNew={this.createNew}
                showNext={this.state.next}
                showPrev={this.state.prev}
                nextRow={this.nextRow}
                previousRow={this.previousRow}
                handleSubmit={this.handleSubmit}
                handleChange={this.handleChange}
                closeRow={this.closeRow}
                viewRow={this.viewRow}
                loading={this.state.isLoading}
                row={this.state.row}
                data={this.state.data}/>
        </div>
    }
}
