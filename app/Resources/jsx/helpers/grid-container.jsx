import React from 'react';
import { render } from 'react-dom';
import { Button, Input } from 'semantic-ui-react'
import Row from '../components/row.jsx';

export default class GridContainer extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            search: "",
            results: []
        };

        this.search = this.search.bind(this);
    }

    search(e) {
        let queryResult = [];
        this.props.rows.forEach(function (row) {
            // Object.keys(row).map(function(key) {
            //     console.log("column: " + row[key]);
            // });

            if (row.name.toLowerCase().indexOf(e.target.value.toLowerCase()) != -1) {
                queryResult.push(row);
            }
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

    render() {
        let columns = [],
            rows = [],
            self = this;

        this.props.columns.forEach(function(column) {
            columns.push(<div key={"column-" + column} className="column">{ column }</div>);
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
