import React from 'react';
import { render } from 'react-dom';

export default class AppComponent extends React.Component {
    constructor(props, context, dataUrl) {
        super(props, context);

        this.state = {
            "row": false,
            "data": null,
            "isLoading": true,
            "dataUrl": null
        };

        this.loadData = this.loadData.bind(this);
        this.closeRow = this.closeRow.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.viewRow = this.viewRow.bind(this);
    }

    loadData() {
        let self = this;
        this.setState({"isLoading": true});

        this.getData(this.state.dataUrl, function(error, result) {
            self.setState({"data": result, "isLoading": false});
        });
    }

    componentDidMount() {
        this.loadData();
    }

    getData(dataUrl, callback) {
        axios.get(dataUrl).then(function (response) {
            callback(null, response.data);
        }).catch(function (error) {
            callback(error, null);
        });
    }

    viewRow(rowId) {
        let self = this;

        this.setState({"isLoading": true, "row": false});

        this.getData(this.state.dataUrl + '/' + rowId, function(error, response) {
            if (error) { self.handleError(error); }

            self.setState({"isLoading": false, "row": response[0]});
        });
    }

    closeRow() {
        this.state.row = false;
        this.setState({"row": false});
        this.loadData();
    }

    handleChange(event) {
        this.state.row[event.target.name] = event.target.value;
        this.setState({"row": this.state.row});
    }
}
