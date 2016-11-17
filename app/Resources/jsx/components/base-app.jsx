import React from 'react';
import { render } from 'react-dom';

export default class BaseApp extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            "row": false,
            "data": null,
            "isLoading": true,
            "dataUrl": null
        };

        this.loadData = this.loadData.bind(this);
        this.closeRow = this.closeRow.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.viewRow = this.viewRow.bind(this);
        this.previousRow = this.previousRow.bind(this);
        this.nextRow = this.nextRow.bind(this);
        this.checkPrevNextButtons = this.checkPrevNextButtons.bind(this);
    }

    checkPrevNextButtons(rowId) {
        let self = this;
        this.state.data.forEach(function(obj, index) {
            if (obj.id == rowId) {
                self.setState({
                    "prev": self.state.data[index-1] !== undefined,
                    "next": self.state.data[index+1] !== undefined
                });
            }
        });
    }

    previousRow() {
        let self = this;
        let currentId = this.state.row.id;

        this.state.data.forEach(function(obj, index) {
            if (obj.id == currentId) {
                if (self.state.data[index-1] !== undefined) {
                    let previousId = self.state.data[index-1].id;

                    self.viewRow(previousId);
                }
            }
        });
    }

    nextRow() {
        let self = this;
        let currentId = this.state.row.id;

        this.state.data.forEach(function(obj, index) {
            if (obj.id == currentId) {
                if (self.state.data[index+1] !== undefined) {
                    let nextId = self.state.data[index+1].id;

                    self.viewRow(nextId);
                }
            }
        });
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

        this.setState({"isLoading": true, "row": false, "next": false, "prev": false});
        this.checkPrevNextButtons(rowId);

        this.getData(this.state.dataUrl + '/' + rowId, function(error, response) {
            if (error) { self.handleError(error); }

            self.setState({"isLoading": false, "row": response[0]});
        });
    }

    closeRow() {
        this.setState({"row": false});
        this.loadData();
    }

    handleChange(event) {
        this.state.row[event.target.name] = event.target.value;
        this.setState({"row": this.state.row});
    }
}
