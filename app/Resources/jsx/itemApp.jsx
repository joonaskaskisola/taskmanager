import React from 'react';
import BaseApp from './components/base-app.jsx';
import ItemView from './views/item-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import request from 'superagent';

export default class ItemApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.app = "item";

        let self = this;

        this.state.categories = [];
        this.getData("/api/category", function (err, data) {
            data.forEach(function(category) {
                self.state.categories.push({'value': category.id, 'text': category.name});
            });
        });

        this.state.units = [];
        this.getData("/api/unit", function (err, data) {
            data.forEach(function(unit) {
                self.state.units.push({'value': unit.id, 'text': unit.name});
            })
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
                        NotificationManager.success("Row added!", "Success");
                        return true;
                    }

                    self.setState({"errors": res.body.error_fields});
                    NotificationManager.error("An error occured", "Problems detected");
                });
        } else {
            request
                .post(BaseApp.getApplicationDataUrl(this.state.app))
                .send(this.state.row)
                .end(function (err, res) {
                    if (!err) {
                        NotificationManager.success("Row added!", "Success");
                        return true;
                    }

                    self.setState({"errors": res.body.error_fields});
                    NotificationManager.error("An error occured", "Problems detected");
                });
        }
    }

    render() {
        return <div>
            <NotificationContainer/>

            <ItemView
                units={this.state.units}
                categories={this.state.categories}

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
        return {
            'name': '',
            'price': 0,
            'category': {
                'id': 0
            },
            'unit': {
                'id': 0
            }
        }
    }
}
