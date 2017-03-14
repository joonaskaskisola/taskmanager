import React from 'react';
import appData from 'json-loader!../../../config/react-app.json';
import { Flag } from 'semantic-ui-react'
import request from 'superagent';
import { useRouterHistory } from 'react-router';
import { createHashHistory } from 'history';

const appHistory = useRouterHistory(createHashHistory)({
    queryKey: false
});

export default class BaseApp extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            "row": false,
            "data": null,
            "isLoading": true,
            "errors": []
        };

        this.loadData = this.loadData.bind(this);
        this.closeRow = this.closeRow.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleSelectChange = this.handleSelectChange.bind(this);
        this.viewRow = this.viewRow.bind(this);
        this.previousRow = this.previousRow.bind(this);
        this.nextRow = this.nextRow.bind(this);
        this.checkPrevNextButtons = this.checkPrevNextButtons.bind(this);
        this.createNew = this.createNew.bind(this);
    }

    handleError(error) {
        console.log(error);
    }

    createNew() {
        this.setState({"row": this.getEmptyModel(), "next": false, "prev": false});
    }

    getEmptyModel() {
        return {};
    }

    checkPrevNextButtons(rowId) {
        let self = this;
        this.state.data.forEach(function (obj, index) {
            if (obj.id === rowId) {
                self.setState({
                    "prev": self.state.data[index - 1] !== undefined,
                    "next": self.state.data[index + 1] !== undefined
                });

                return false;
            }

            return true;
        });
    }

    getPreviousId(currentId, data, callback) {
        data.forEach(function (obj, index) {
            if (obj.id === currentId && data[index - 1] !== undefined) {
                return callback(data[index - 1].id);
            }
        });
    }

    previousRow() {
        let self = this;

        this.getPreviousId(
            this.state.row.id,
            this.state.data,
            function (prevId) {
                appHistory.push("/" + self.state.app + "/" + prevId);
                self.viewRow(prevId);
            }
        );
    }

    getNextId(currentId, data, callback) {
        data.forEach(function (obj, index) {
            if (obj.id === currentId && data[index + 1] !== undefined) {
                return callback(data[index + 1].id);
            }
        });
    }

    nextRow() {
        let self = this;

        this.getNextId(
            this.state.row.id,
            this.state.data,
            function (nextId) {
                appHistory.push("/" + self.state.app + "/" + nextId);
                self.viewRow(nextId);
            }
        );
    }

    loadData(cb) {
        let self = this;

        if (this.state.app !== 'default') {
            this.setState({"isLoading": true});

            this.getData(BaseApp.getApplicationDataUrl(this.state.app), function (error, result) {
                self.setState({"data": result, "isLoading": false});

                if (typeof cb !== 'undefined') {
                    cb();
                }
            });
        }
    }

    componentDidMount() {
        let self = this;

        if (this.props.params.hasOwnProperty('id')) {
            this.loadData(function() {
                self.viewRow(parseInt(self.props.params.id));
            });
        } else {
            this.loadData();
        }
    }

    getData(dataUrl, callback) {
        request
            .get(dataUrl)
            .end(function (err, res) {
                callback(err, res.body);
            });
    }

    viewRow(rowId) {
        let self = this;

        this.setState({"next": false, "prev": false});
        this.checkPrevNextButtons(rowId);

        if (BaseApp.isDetailedApiCallNeeded(this.state.app)) {
            this.setState({"isLoading": true});

            this.getData(BaseApp.getApplicationDataUrl(this.state.app) + '/' + rowId, function (error, response) {
                if (error) {
                    self.handleError(error);
                }

                self.setState({"isLoading": false, "row": response[0]});
            });
        } else {
            this.state.data.every(function (obj, index) {
                if (obj.id === rowId) {
                    self.setState({"row": obj});
                    return false;
                }

                return true;
            });
        }
    }

    closeRow() {
        this.setState({"row": false});
        this.loadData();
    }

    handleSelectChange(name, value) {
        this.state.row[value.name] = value.value;
        this.setState({"row": this.state.row});
    }

    handleChange(event) {
        console.log(event.target.name, event.target.value);

        this.state.row[event.target.name] = event.target.value;
        this.setState({"row": this.state.row});
    }

    static getApplicationDataUrl(appName) {
        return appData[appName]['url'];
    }

    static isDetailedApiCallNeeded(appName) {
        return appData[appName]['fetchDetailedRowDetails'];
    }

    static getApplicationHeader(appName) {
        return appData[appName]['title'];
    }

    static getValidFlags() {
        let flags = Flag._meta.props.name, validFlags = [];

        flags.forEach(function (flag) {
            if (flag.length === 2) {
                validFlags.push({
                    "value": flag.toUpperCase(),
                    "text": flag.toUpperCase(),
                    "flag": flag
                });
            }
        });

        return validFlags;
    }
}
