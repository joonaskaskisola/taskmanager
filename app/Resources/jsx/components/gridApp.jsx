import React from 'react';
import { render } from 'react-dom';

export default class GridApp extends React.Component {
    constructor(props, context, dataUrl) {
        super(props, context);

        this.state = {
            "row": false,
            "data": null,
            "isLoading": true,
            "dataUrl": dataUrl
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
            self.setState({"isLoading": false, "row": response[0]});
        });
    }

    closeRow() {
        this.state.row = false;
        this.setState({"row": false});
        this.loadData();
    }

    handleSubmit(event) {
        event.preventDefault();

        let self = this;
        this.setState({"isLoading": true});

        axios.put(this.state.dataUrl, this.state.row).then(function (response) {
            self.setState({"isLoading": false});
            self.closeRow();
        }).catch(function (error) {
            // @todo: implement
        });
    }

    handleChange(event) {
        this.state.row[event.target.name] = event.target.value;
        this.setState({"row": this.state.row});
    }

    render() {
        return <div></div>
    }
}
