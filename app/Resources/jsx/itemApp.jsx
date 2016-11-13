import React from 'react';
import { render } from 'react-dom';
import { Route, ReactRouter, Router, browserHistory } from 'react-router';
import Select from 'react-select';

export default class ItemApp extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            "item": false,
            "items": null,
            "isLoading": true,
            "categories": null,
            "units": null
        };

        this.loadData = this.loadData.bind(this);
        this.closeItem = this.closeItem.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleUnitSelectChange = this.handleUnitSelectChange.bind(this);
        this.handleCategorySelectChange = this.handleCategorySelectChange.bind(this);
        this.viewItem = this.viewItem.bind(this);
    }

    loadData() {
        let self = this;
        this.setState({"isLoading": true});

        this.getCategories(function(error, result) {
            self.setState({"categories": result});

            self.getUnits(function(error, result) {
                self.setState({"units": result});

                self.getItems(function(error, result) {
                    self.setState({"items": result, "isLoading": false});
                });
            });
        });
    }

    componentDidMount() {
        this.loadData();
    }

    getUnits(callback) {
        axios.get("/api/units").then(function (response) {
            let units = [];
            response.data.forEach(function(unit) {
                units.push({
                    'value': unit.id,
                    'label': unit.name
                })
            });

            callback(null, units);
        }).catch(function (error) {
            callback(error, null);
        });
    }

    getCategories(callback) {
        axios.get("/api/categories").then(function (response) {
            let categories = [];
            response.data.forEach(function(item) {
                categories.push({
                    'value': item.id,
                    'label': item.name
                })
            });

            callback(null, categories);
        }).catch(function (error) {
            callback(error, null);
        });
    }

    getItems(callback) {
        axios.get("/api/items").then(function (response) {
            callback(null, response.data);
        }).catch(function (error) {
            callback(error, null);
        });
    }

    viewItem(itemId) {
        let self = this;

        this.setState({"isLoading": true, "item": false});

        this.state.items.forEach(function(item) {
            if (item.id == itemId) {
                self.setState({"isLoading": false, "item": item});
            }
        });
    }

    closeItem() {
        this.state.item = false;
        this.setState({"item": false});
        this.loadData();
    }

    handleSubmit(event) {
        event.preventDefault();

        let self = this;
        this.setState({"isLoading": true});

        axios.put("/api/items", this.state.item).then(function (response) {
            self.setState({"isLoading": false});
            self.closeItem();
        }).catch(function (error) {
            // @todo: implement
        });
    }

    handleCategorySelectChange(category) {
        this.state.item['category'] = {
            'id': category.value,
            'name': category.label
        };

        this.setState({"item": this.state.item});
    }

    handleUnitSelectChange(unit) {
        this.state.item['unit'] = {
            'id': unit.value,
            'name': unit.label
        };

        this.setState({"item": this.state.item});
    }

    handleChange(event) {
        this.state.item[event.target.name] = event.target.value;
        this.setState({"item": this.state.item});
    }

    renderItem() {
        return <div>
            <button onClick={this.closeItem}>Close item</button>

            <form onSubmit={this.handleSubmit}>
                <Select
                    name="category.id"
                    value={this.state.item.category.id}
                    options={this.state.categories}
                    onChange={this.handleCategorySelectChange}/>

                <br />

                <Select
                    name="unit.id"
                    value={this.state.item.unit.id}
                    options={this.state.units}
                    onChange={this.handleUnitSelectChange}/>

                <br />

                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input
                        className="mdl-textfield__input"
                        type="text"
                        id="name" name="name"
                        value={this.state.item.name}
                        onChange={this.handleChange}/>
                    <label className="mdl-textfield__label" htmlFor="name">Name</label>
                </div>

                <br />

                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input
                        className="mdl-textfield__input"
                        type="text"
                        id="price" name="price"
                        value={this.state.item.price}
                        onChange={this.handleChange}/>
                    <label className="mdl-textfield__label" htmlFor="price">Price</label>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    }

    renderItems() {
        let self = this;

        return <table className="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
            <thead>
                <tr>
                    <th className="mdl-data-table__cell--non-numeric">category</th>
                    <th className="mdl-data-table__cell--non-numeric">unit</th>
                    <th className="mdl-data-table__cell--non-numeric">name</th>
                    <th className="mdl-data-table__cell--numeric">price</th>
                </tr>
            </thead>

            <tbody>
            {this.state.items.map(function (listValue) {
                return <tr key={listValue.id} onClick={() => { self.viewItem(listValue.id) }} className="pointertr">
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.category.name }</td>
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.unit.name }</td>
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.name }</td>
                    <td className="mdl-data-table__cell--numeric">{ listValue.price }</td>
                </tr>
            })}
            </tbody>
        </table>
    }

    render() {
        if (this.state.isLoading) {
            return <div>
                Loading..
            </div>
        } else {
            if (this.state.item) {
                return this.renderItem();
            } else {
                return this.renderItems();
            }
        }
    }
}

render(
    <ItemApp/>,
    document.getElementById('itemApp')
);