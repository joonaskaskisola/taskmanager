import React from 'react';
import { render } from 'react-dom';
import { Button, Input } from 'semantic-ui-react'
import Row from '../components/row.jsx';
import Sort from '../components/sort.jsx';

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
        let queryResult = [], added, self = this;

        this.props.rows.forEach(function (row) {
            added = false;
            e.target.value.toLowerCase().split(' ').forEach(function(word) {
                if (!added) {
                    Object.keys(row).map(function(key) {
                        if (self.props.fields.indexOf(key) != -1
                            && !added
                            && isNaN(row[key])
                            && row[key].toLowerCase().indexOf(word) != -1
                        ) {
                            added = true;
                            queryResult.push(row);
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
        let columns = [],
            rows = [],
            self = this;

        this.props.columns.forEach(function(column, i) {
            columns.push(<div name={self.props.fields[i]} onClick={self.sortByColumn} key={"column-" + column} className="column clickable">{ column }</div>);
        });

        this.state.results.forEach(function(row) {
            if (self.props.viewRow) {
                rows.push(<Row fields={self.props.fields} row={row} key={row.id} viewRow={self.props.viewRow}/>);
            } else {
                rows.push(<Row fields={self.props.fields} row={row} key={row.id} viewRow={function(rowId) { }}/>);
            }
        });

        return <div>
            <div className="ui computer equal width grid">
                <div className="row secondary">
                    {this.props.createNew && <div className="column">
                        <Button primary onClick={this.props.createNew}>Add new</Button>
                    </div>}

                    {this.props.search && <Input
                        value={this.state.search}
                        onChange={this.search}
                        style={{marginRight: "20px"}}
                        focus
                        icon="search"
                        placeholder='Search...' />}
                </div>
            </div>

            <div className="ui computer equal width grid">
                <div className="row blue">
                    {columns}
                </div>

                {rows}
            </div>
        </div>
    }
}
