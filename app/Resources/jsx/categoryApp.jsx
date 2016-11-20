import React from 'react';
import {render} from 'react-dom';
import BaseApp from './components/base-app.jsx';
import CategoryView from './views/category-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';

export default class CategoryApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.dataUrl = "/api/category";
        this.state.loadExtraInfo = false;

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        axios.put(this.state.dataUrl, this.state.row).then(function (response) {
            NotificationManager.success("Row updated!", "Success");
        }).catch(function (error) {
            NotificationManager.error(error.toString(), "Problems detected");
        });
    }

    render() {
        return <div>
            <NotificationContainer/>

            <div className="ui segment">
                <div className={"ui inverted  " + (this.state.isLoading ? "active" : "") + " dimmer"}>
                    <div className="ui loader"></div>
                </div>

                <CategoryView
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
        </div>
    }
}

render(
    <CategoryApp/>,
    document.getElementById('categoryApp')
);
