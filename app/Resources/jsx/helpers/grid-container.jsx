import React from 'react';
import { Button, Input, Menu, Item } from 'semantic-ui-react'
import Row from '../components/row.jsx';
import Sort from '../components/sort.jsx';
import { Link } from 'react-router';

export default class GridContainer extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            search: "",
            results: [],
            sortBy: "",
            sortOrder: true
        };

        this.search = this.search.bind(this);
        this.sortByColumn = this.sortByColumn.bind(this);
    }

    search(e) {
        /**
         * Don't.
         */

        let queryResult = [], added, self = this;

        this.props.rows.forEach(function (row) {
            added = false;
            e.target.value.toLowerCase().split(' ').forEach(function(word) {
                if (!added) {
                    console.log("object keys:", Object.keys(row), self.props);

                    Object.keys(row).map(function(key) {
                        if (self.props.fields.indexOf(key) !== -1
                            && !added
                            && isNaN(row[key])
                            && row[key].toLowerCase().indexOf(word) !== -1
                        ) {
                            added = true;
                            queryResult.push(row);
                        } else if (!added && typeof row[key] === "object") {
                            self.props.fields.forEach(function(i_field) {
                                if (i_field.indexOf('.') !== -1) {
                                    console.log(row);
                                    console.log(i_field, key);

                                    let e = row;
                                    i_field.split('.').forEach(function(s) {
                                        e = e[s];

                                        if (typeof e === "string") {
                                            console.log("string!", e, "haetaan:", word);

                                            if (e.toLowerCase().indexOf(word) !== -1) {
                                                added = true;
                                                queryResult.push(row);
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });

        this.setState({
            "results": queryResult,
            "search": e.target.value
        });
    }

    componentDidMount() {
        this.setState({
            "results": this.props.rows
        });
    }

    sortByColumn(e) {
        let sortBy = e.target.getAttribute('name');

        if (this.state.sortBy !== sortBy) {
            this.setState({"sortOrder": true});
        } else {
            this.setState({"sortOrder": !this.state.sortOrder});
        }

        this.state.results.sort(
            Sort.sortBy(
                e.target.getAttribute('name'),
                this.state.sortOrder,
                function (a) {
                    return a.toUpperCase()
                }
            )
        );

        this.setState({"results": this.state.results, "sortBy": sortBy});
    }

    render() {
        let columns = [], rows = [], self = this;

        /**
         * Header
         */
        this.props.columns.forEach(function(column, i) {
            columns.push(<div name={self.props.fields[i]} onClick={self.sortByColumn} key={"column-" + column} className="column clickable">{ column }</div>);
        });

        /**
         * Body
         */
        this.state.results.forEach(function(row) {
            if (self.props.viewRow) {
                rows.push(<Row app={self.props.app} fields={self.props.fields} row={row} key={row.id} viewRow={self.props.viewRow}/>);
            } else {
                rows.push(<Row app={self.props.app} fields={self.props.fields} row={row} key={row.id} viewRow={function(rowId) { }}/>);
            }
        });

        return <div>
            <Menu pointing>
                <Menu.Item name={this.props.app} active={false} />
                <Menu.Menu position='right'>
                    <Menu.Item>
                        {this.props.createNew && <div className="column">
                            <Link to={"/" + this.props.app + "/new"} onClick={this.props.createNew}>
                                <Button primary>Create new</Button>
                            </Link>
                        </div>}
                    </Menu.Item>

                    <Menu.Item>
                        {this.props.search && <Input
                            value={this.state.search}
                            onChange={this.search}
                            style={{marginRight: "20px"}}
                            focus
                            icon="search"
                            placeholder='Search...' />}
                    </Menu.Item>
                </Menu.Menu>
            </Menu>

            <div className="ui segment">
                <div className={"ui inverted  " + (this.props.isLoading ? "active" : "") + " dimmer"}>
                    <div className="ui loader"></div>
                </div>

                <div className="ui computer equal width grid">
                    <div className="row blue">
                        {columns}
                    </div>

                    {rows}
                </div>
            </div>
        </div>
    }
}
