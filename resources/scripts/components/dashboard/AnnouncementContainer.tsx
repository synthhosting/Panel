import React, { useEffect, useState } from 'react';
import getAnnouncements from '@/api/helionix/getAnnouncements';
import PageContentBlock from '@/components/elements/PageContentBlock';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import { Button } from '@/components/elements/button/index';
import { faBell} from '@fortawesome/free-solid-svg-icons';
import tw from 'twin.macro';

interface Announcement {
    id: number;
    title: string;
    description: string;
    created_at: string,
}

export default () => {
    const [announcements, setAnnouncements] = useState<Announcement[]>([]);
    const [selectedAnnouncement, setSelectedAnnouncement] = useState<Announcement | null>(null);

    useEffect(() => {
        const fetchAnnouncements = async () => {
            try {
                const data = await getAnnouncements();
                setAnnouncements(data);
            } catch (error) {
                // Handle error if needed
            }
        };

        fetchAnnouncements();
    }, []);

    const handleReadMore = (announcement: Announcement) => {
        setSelectedAnnouncement(announcement);
    };

    const handleBack = () => {
        setSelectedAnnouncement(null);
    };

    return (
        <PageContentBlock title={'Announcement'}>
            <div css={tw`w-full`}>
                {announcements.length > 0 ? (
                    selectedAnnouncement ? (
                        <div css={tw`w-full bg-helionix-color2 rounded-xl p-4 shadow-md`}>
                            <p css={tw`text-base font-semibold uppercase`}>{selectedAnnouncement.title}</p>
                            <p css={tw`mt-4`} dangerouslySetInnerHTML={{ __html: selectedAnnouncement.description }}></p>
                            <div css={tw`w-full flex items-center mt-4`}>
                                <Button onClick={handleBack}>Back</Button>
                                <span css={tw`ml-auto`}>
                                    {new Date(selectedAnnouncement.created_at).toLocaleDateString('en-US', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric',
                                    })}
                                </span>
                            </div>
                        </div>
                    ) : (
                        <div css={tw`flex flex-col gap-2`}>
                            {announcements.map(announcement => (
                                <div css={tw`w-full bg-helionix-color2 rounded-xl p-4 shadow-md`} key={announcement.id}>
                                    <p css={tw`text-base font-semibold uppercase`}>{announcement.title}</p>
                                    <p css={tw`break-words line-clamp-2 mt-4`} dangerouslySetInnerHTML={{ __html: announcement.description }}></p>
                                    <div css={tw`w-full flex items-center mt-4`}>
                                        <Button onClick={() => handleReadMore(announcement)}>Read More</Button>
                                        <span css={tw`ml-auto`}>
                                            {new Date(announcement.created_at).toLocaleDateString('en-US', {
                                                day: '2-digit',
                                                month: 'short',
                                                year: 'numeric',
                                            })}
                                        </span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )
                ) : (
                    <div css={tw`flex items-center justify-center`}>
                        <p>There is no announcement.</p>
                    </div>
                )}
            </div>
        </PageContentBlock>
    );
};