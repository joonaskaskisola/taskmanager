import React from 'react';
import {render} from 'react-dom';
import BaseApp from './components/base-app.jsx';
import ItemView from './views/item-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import request from 'superagent';

export default class ItemApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.app = "item";
        this.state.loadExtraInfo = true;
        this.state.categories = [];
        this.state.units = [];

        let self = this;

        this.getData("/api/category", function (err, data) {
            data.forEach(function(category) {
                self.state.categories.push({'value': category.id, 'text': category.name});
            })
        });

        this.getData("/api/unit", function (err, data) {
            data.forEach(function(unit) {
                self.state.units.push({'value': unit.id, 'text': unit.name});
            })
        });

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

            <div className="ui segment">
                <div className={"ui inverted  " + (this.state.isLoading ? "active" : "") + " dimmer"}>
                    <div className="ui loader"></div>
                </div>

                <ItemView
                    e={this.state.errors}
                    createNew={this.createNew}
                    units={this.state.units}
                    categories={this.state.categories}
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
    <ItemApp/>,
    document.getElementById('itemApp')
);
