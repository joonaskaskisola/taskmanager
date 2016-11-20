import React from 'react';
import { render } from 'react-dom';
import { Button } from 'semantic-ui-react'

export default class GridContainer extends React.Component {
    render() {
        let columns = [];
        this.props.columns.forEach(function(column) {
            columns.push(<div key={"column-" + column} className="column">{ column }</div>);
        });

        return <div>
            <div className="ui computer equal width grid">
                <div className="row secondary">
                    <div className="column"><Button primary onClick={this.props.createNew}>Add new</Button></div>
                </div>
            </div>

            <div className="ui computer equal width grid">
                <div className="row blue">
                    {columns}
                </div>

                {this.props.rows}
            </div>
        </div>
    }
}
