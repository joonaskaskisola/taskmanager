import React from 'react';
import { render } from 'react-dom';
import TextField from '../components/text.jsx';
import TextAreaField from '../components/textArea.jsx';
import SelectField from '../components/select.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import { Divider } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';

export default class InboxView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    render() {
        if (this.props.loading && !this.props.row) {
            return <div></div>
        }

        if (this.props.row) {
            return <div>
                <Divider horizontal>PM #{this.props.row.id}</Divider>

                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <NavigationButtons
                        header={true}
                        nextRow={this.props.nextRow}
                        previousRow={this.props.previousRow}
                        showPrev={this.props.showPrev}
                        showNext={this.props.showNext}/>

                    <h4 className="ui dividing header">Shipping Information</h4>

                    <div className="field">
                        <TextField width="twelve" name="subject" label="Subject" value={this.props.row.subject} handleChange={this.props.handleChange} />
                    </div>

                    <div className="two fields">
                        {this.props.row.id && <TextField width="six" name="timestamp" label="Timestamp" value={this.props.row.timestamp} />}
                        {this.props.row.id && <SelectField width="six" name="from_user" label="From" options={this.props.users} value={this.props.row.from_user} handleChange={this.props.handleSelectChange} />}
                        {!this.props.row.id && <SelectField width="twelve" name="to_user" label="To" options={this.props.users} value={this.props.row.to_user} handleChange={this.props.handleSelectChange} />}
                    </div>

                    <div className="field">
                        <TextAreaField width="twelve" name="message" label="Message" value={this.props.row.message} handleChange={this.props.handleChange} />
                    </div>

                    <NavigationButtons
                        footer={true}
                        closeRow={this.props.closeRow}
                        handleSubmit={this.props.handleSubmit}/>
                </div>
            </div>
        }

        return <GridContainer
            search={true}
            fields={['subject', 'timestamp', 'from']}
            columns={['Subject', 'Timestamp', 'Sender']}
            rows={this.props.data}
            viewRow={this.props.viewRow}
            createNew={this.props.createNew}/>
    }
}
